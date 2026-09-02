"""Match edited PNGs from supplied ZIP archives to vehicle covers and import safe matches."""

from __future__ import annotations

import json
import re
import shutil
from datetime import datetime
from pathlib import Path
from zipfile import ZipFile

import cv2
import numpy as np


ROOT = Path(__file__).resolve().parents[1]
COVERS = ROOT / "storage/app/public/vehicles"
ORIGINALS = ROOT / "storage/app/public/vehicles_orijinal_yedek"
DOWNLOADS = Path(r"C:\Users\aykut\Downloads")
ZIPS = [
    DOWNLOADS / "konusmadaki_butun_resimler.zip",
    DOWNLOADS / "tum_araba_resimleri (2).zip",
    DOWNLOADS / "tum_gorseller_en_bastan_toplu.zip",
]

GROUPS = {
    148: [290, 27], 440: [364, 327, 30, 320, 453], 471: [446, 507, 362],
    23: [357], 121: [74], 96: [87], 144: [200, 65], 292: [265, 60],
    510: [116, 489, 54], 511: [267, 365], 88: [39], 156: [519],
    100: [414], 520: [505], 494: [128], 491: [351], 425: [395],
    275: [56, 417], 368: [383, 98, 277], 167: [165], 430: [185],
    439: [179, 234], 444: [377, 14, 322, 348, 69, 19, 248],
    488: [141], 431: [212], 235: [175],
}
REMAP = {old: keep for keep, olds in GROUPS.items() for old in olds}


def feature(image: np.ndarray) -> tuple[np.ndarray, np.ndarray]:
    image = cv2.resize(image, (96, 96))
    gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
    dct = cv2.dct(cv2.resize(gray, (32, 32)).astype(np.float32))[:12, :12]
    dct = (dct - dct.mean()) / (dct.std() + 1e-6)
    hsv = cv2.cvtColor(image, cv2.COLOR_BGR2HSV)
    hist = np.concatenate([
        cv2.calcHist([hsv], [channel], None, [bins], [0, 256]).ravel()
        for channel, bins in ((0, 24), (1, 16), (2, 16))
    ])
    hist /= hist.sum() + 1e-9
    return dct.ravel(), hist


def score(a: tuple[np.ndarray, np.ndarray], b: tuple[np.ndarray, np.ndarray]) -> float:
    return float(np.mean((a[0] - b[0]) ** 2) + 5 * np.sum(np.abs(a[1] - b[1])))


def explicit_id(name: str) -> int | None:
    name = re.sub(r"^\d{3}_", "", name)
    match = re.search(r"(?:manual_|plaka[^_]*_)(\d{3})(?:\D|$)", name, re.I)
    if match:
        return int(match.group(1))
    match = re.search(r"(?:^|_)(\d{3})_plaka", name, re.I)
    return int(match.group(1)) if match else None


def main() -> None:
    refs = {}
    for path in ORIGINALS.glob("*.webp"):
        if path.stem.isdigit():
            image = cv2.imread(str(path))
            if image is not None:
                refs[int(path.stem)] = feature(image)

    candidates = []
    for zip_path in ZIPS:
        with ZipFile(zip_path) as archive:
            for info in archive.infolist():
                if info.is_dir() or Path(info.filename).suffix.lower() != ".png":
                    continue
                data = archive.read(info)
                image = cv2.imdecode(np.frombuffer(data, np.uint8), cv2.IMREAD_COLOR)
                if image is None:
                    continue
                forced = explicit_id(Path(info.filename).stem)
                if forced is not None:
                    target = REMAP.get(forced, forced)
                    confidence = -1.0
                    safe = True
                else:
                    image_feature = feature(image)
                    ranked = sorted((score(image_feature, ref), vehicle_id) for vehicle_id, ref in refs.items())
                    best, second = ranked[0], ranked[1]
                    target = REMAP.get(best[1], best[1])
                    confidence = best[0]
                    safe = best[0] <= 0.80 or (best[0] <= 1.20 and second[0] - best[0] >= 0.25)
                if safe:
                    # Explicit v2 wins; otherwise prefer the strongest visual match.
                    priority = -2.0 if "v2" in info.filename.lower() else confidence
                    candidates.append({
                        "target": target, "priority": priority, "score": confidence,
                        "zip": str(zip_path), "file": info.filename, "data": data,
                    })

    chosen = {}
    for item in candidates:
        target = item["target"]
        if target not in chosen or item["priority"] < chosen[target]["priority"]:
            chosen[target] = item

    stamp = datetime.now().strftime("%Y%m%d-%H%M%S")
    backup = ROOT / f"storage/app/public/vehicle_zip_import_backup_{stamp}"
    backup.mkdir(parents=True, exist_ok=True)
    report = []
    for target, item in sorted(chosen.items()):
        destination = COVERS / f"{target}.webp"
        if not destination.exists():
            continue
        shutil.copy2(destination, backup / destination.name)
        image = cv2.imdecode(np.frombuffer(item["data"], np.uint8), cv2.IMREAD_COLOR)
        cv2.imwrite(str(destination), image, [cv2.IMWRITE_WEBP_QUALITY, 90])
        report.append({k: v for k, v in item.items() if k != "data"})

    report_path = backup / "import-report.json"
    report_path.write_text(json.dumps(report, ensure_ascii=False, indent=2), encoding="utf-8")
    print(f"updated={len(report)}")
    print(f"backup={backup}")
    print(f"report={report_path}")
    print("ids=" + ",".join(str(row["target"]) for row in report))


if __name__ == "__main__":
    main()
