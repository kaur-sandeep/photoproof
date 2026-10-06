from dataclasses import dataclass
from pathlib import Path
import os
from dotenv import load_dotenv

load_dotenv(Path(__file__).with_name('.env'))


def _labels(name: str) -> set[str]:
    return {value.strip().lower() for value in os.getenv(name, '').split(',') if value.strip()}


@dataclass(frozen=True)
class Settings:
    host: str = os.getenv('MODERATION_HOST', '127.0.0.1')
    port: int = int(os.getenv('MODERATION_PORT', '8001'))
    weapon_threshold: float = float(os.getenv('WEAPON_THRESHOLD', '0.80'))
    nsfw_threshold: float = float(os.getenv('NSFW_THRESHOLD', '0.80'))
    violence_threshold: float = float(os.getenv('VIOLENCE_THRESHOLD', '0.85'))
    max_image_size: int = int(float(os.getenv('MAX_IMAGE_SIZE_MB', '15')) * 1024 * 1024)
    weapon_model_path: Path = Path(os.getenv('WEAPON_MODEL_PATH', 'models/weapons.pt'))
    weapon_labels: set[str] = None
    nsfw_model_path: str = os.getenv('NSFW_MODEL_PATH', '')
    nsfw_labels: set[str] = None
    violence_model_path: str = os.getenv('VIOLENCE_MODEL_PATH', '')
    violence_labels: set[str] = None

    def __post_init__(self):
        object.__setattr__(self, 'weapon_labels', _labels('WEAPON_LABELS'))
        object.__setattr__(self, 'nsfw_labels', _labels('NSFW_LABELS'))
        object.__setattr__(self, 'violence_labels', _labels('VIOLENCE_LABELS'))


settings = Settings()
