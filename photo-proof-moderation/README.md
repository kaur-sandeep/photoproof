# PhotoProof local image moderation

This service receives an image multipart upload from Laravel and performs inference on the same machine/private network. It has no URL input, no cloud inference client, and no image storage. Bind it to `127.0.0.1` unless Laravel is on a separately firewalled private host.

## Models and supported categories

The configured local model is [`cosgun99/gun-knife-yolo11n`](https://huggingface.co/cosgun99/gun-knife-yolo11n), downloaded as `models/weapons.pt`. Its model card is MIT-licensed and documents exactly two supported labels: `gun` and `knife`. It is intended for academic/comparative use and must be independently validated before any safety-critical production use. A generic YOLO COCO model must **not** be used: it does not provide reliable firearm/knife categories. Inspect the installed model labels with:

```powershell
venv\Scripts\python -c "from ultralytics import YOLO; m=YOLO('models\weapons.pt'); print(m.names)"
```

Set `WEAPON_LABELS` to exact labels printed by that command. The service starts only when every configured label is present in the local model. Therefore, the installed model's labels are the exact supported weapon categories; it makes no claim to identify any other weapon. It blocks only detections for those configured labels at `WEAPON_THRESHOLD`.

NSFW is optional. Set `NSFW_MODEL_PATH` to a fully downloaded local Transformers image-classification model directory and set `NSFW_LABELS` to its exact unsafe labels. It returns the highest probability across those labels. Violence/gore uses the identical optional local-classifier mechanism via `VIOLENCE_MODEL_PATH` and `VIOLENCE_LABELS`. If either path is empty, that category is reported as `0.0`; it is not detected.

You are responsible for model provenance, evaluation, and license compliance. Ultralytics is AGPL-3.0 or commercial, depending on your use; review its licensing before production. The Python package licenses do not grant rights to downloaded model weights.

## Windows setup

Use Python 3.10–3.12 (64-bit; match a PyTorch build supported by your CPU/GPU):

```powershell
cd photo-proof-moderation
python --version
python -m venv venv
venv\Scripts\activate
pip install -r requirements.txt
Copy-Item .env.example .env
# Copy your evaluated, local weapon model to models\weapons.pt and edit .env.
python app.py
```

It listens at `http://192.168.0.121:8001` by default. Do not publish port 8001 through a reverse proxy or firewall. If Laravel runs on a separate private host, bind only to its private interface, firewall the port to that host, and set `IMAGE_MODERATION_URL` to that RFC1918 address.

Test with a local file only:

```powershell
curl.exe -X POST http://192.168.0.121:8001/moderate -F "file=@C:\path\to\local-test.jpg"
```

## Laravel configuration

Add these values to Laravel's existing `.env`, then run `php artisan config:clear`:

```dotenv
IMAGE_MODERATION_ENABLED=true
IMAGE_MODERATION_URL=http://192.168.0.121:8001
IMAGE_MODERATION_TIMEOUT=30
IMAGE_MODERATION_FAIL_CLOSED=true
```

`IMAGE_MODERATION_URL` is restricted in code to `localhost`, loopback, or a literal private/reserved IP address. A public hostname/IP is rejected before any image upload. With moderation enabled, malformed/offline/invalid-service responses fail closed: Laravel returns 503 and no permanent image is stored. Rejections return 422, create a `photo_moderations` audit row, and do not create a PhotoDetail, thumbnail, tracking row, notification, or successful-upload email.

## Acceptance checklist

Use locally held, authorized test fixtures: normal building/person (allowed); a sample for every configured weapon label (rejected); NSFW and violence fixtures only if their respective local models are configured (rejected); corrupt and over-15MB files (rejected). Also stop the Python service and verify Laravel returns `IMAGE_MODERATION_UNAVAILABLE`. For each rejection, inspect `storage/app/public/photos` and `photo_details` to confirm nothing was created. Verify an approved image follows the normal upload workflow, then check daily-limit and blocked-device behavior unchanged.

The model is loaded once at process start. Uploaded bytes remain in memory while processing and are not saved by this service. Logs contain outcome/timing/error type only—not image data, paths, or request metadata.
