"""Private, local-only image moderation service. It never accepts image URLs."""
from contextlib import asynccontextmanager
from io import BytesIO
import logging
from pathlib import Path
from time import perf_counter

from fastapi import FastAPI, File, HTTPException, Request, UploadFile
from fastapi.responses import JSONResponse
from PIL import Image, UnidentifiedImageError
from ultralytics import YOLO

from config import settings

logging.basicConfig(level=logging.INFO, format='%(asctime)s %(levelname)s %(message)s')
logger = logging.getLogger('photo-proof-moderation')
weapon_model = None
nsfw_classifier = None
violence_classifier = None


def load_local_classifier(path: str):
    """Load a Transformers image classifier strictly from a local directory."""
    if not path:
        return None
    directory = Path(path)
    if not directory.is_dir():
        raise RuntimeError(f'Configured local classifier directory does not exist: {directory.name}')
    from transformers import pipeline
    return pipeline('image-classification', model=str(directory), local_files_only=True)


@asynccontextmanager
async def lifespan(_: FastAPI):
    global weapon_model, nsfw_classifier, violence_classifier
    if not settings.weapon_model_path.is_file():
        raise RuntimeError('WEAPON_MODEL_PATH must reference an installed local weapon-capable YOLO weights file.')
    weapon_model = YOLO(str(settings.weapon_model_path))
    labels = {str(label).lower() for label in weapon_model.names.values()}
    if not settings.weapon_labels:
        raise RuntimeError('WEAPON_LABELS must contain exact labels from the installed weapon model.')
    missing = settings.weapon_labels - labels
    if missing:
        raise RuntimeError('WEAPON_LABELS contains labels not present in the local model.')
    nsfw_classifier = load_local_classifier(settings.nsfw_model_path)
    violence_classifier = load_local_classifier(settings.violence_model_path)
    logger.info('Local models ready; weapon labels enabled: %s', sorted(settings.weapon_labels))
    yield


app = FastAPI(title='PhotoProof Local Moderation', docs_url=None, redoc_url=None, lifespan=lifespan)


@app.middleware('http')
async def size_limit(request: Request, call_next):
    length = request.headers.get('content-length')
    if length and int(length) > settings.max_image_size + 1024 * 1024:
        return JSONResponse({'detail': 'Request too large'}, status_code=413)
    return await call_next(request)


def classify(classifier, image: Image.Image, labels: set[str]) -> float:
    if classifier is None:
        return 0.0
    results = classifier(image)
    return max((float(item['score']) for item in results if str(item['label']).lower() in labels), default=0.0)


@app.post('/moderate')
async def moderate(file: UploadFile = File(...)):
    started = perf_counter()
    if file.content_type not in {'image/jpeg', 'image/png'}:
        raise HTTPException(status_code=415, detail='Only JPEG and PNG images are supported.')
    content = await file.read(settings.max_image_size + 1)
    if len(content) > settings.max_image_size:
        raise HTTPException(status_code=413, detail='Image exceeds maximum size.')
    try:
        image = Image.open(BytesIO(content))
        image.verify()
        image = Image.open(BytesIO(content)).convert('RGB')
    except (UnidentifiedImageError, OSError, ValueError):
        raise HTTPException(status_code=422, detail='Malformed image.')

    detections, weapon = [], 0.0
    for result in weapon_model(image, verbose=False):
        for box in result.boxes:
            label = str(weapon_model.names[int(box.cls[0])]).lower()
            confidence = float(box.conf[0])
            if label in settings.weapon_labels:
                weapon = max(weapon, confidence)
                detections.append({'category': 'weapon', 'label': label, 'confidence': round(confidence, 5)})

    nsfw = classify(nsfw_classifier, image, settings.nsfw_labels)
    violence = classify(violence_classifier, image, settings.violence_labels)
    categories = {'weapon': round(weapon, 5), 'nsfw': round(nsfw, 5), 'violence': round(violence, 5)}
    reason = ('weapon_detected' if weapon >= settings.weapon_threshold else
              'nsfw_detected' if nsfw >= settings.nsfw_threshold else
              'violence_detected' if violence >= settings.violence_threshold else None)
    logger.info('Moderation complete allowed=%s reason=%s duration_ms=%d', not bool(reason), reason, (perf_counter() - started) * 1000)
    return {'success': True, 'allowed': not bool(reason), 'reason': reason,
            'categories': categories, 'detections': detections}


@app.exception_handler(Exception)
async def unexpected_error(_: Request, error: Exception):
    logger.exception('Moderation processing failed: %s', type(error).__name__)
    return JSONResponse({'success': False, 'detail': 'Internal moderation error'}, status_code=500)


if __name__ == '__main__':
    import uvicorn
    uvicorn.run(app, host=settings.host, port=settings.port)
