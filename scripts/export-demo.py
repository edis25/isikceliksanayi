#!/usr/bin/env python3
"""
Işık Çelik — statik demo dökümü.
Kullanım (php sunucusu localhost:8123'te açıkken):
  python3 scripts/export-demo.py            → docs/         (GitHub Pages, mutlak bağlantılar)
  python3 scripts/export-demo.py netlify    → dist-netlify/ (kök-göreli bağlantılar, her alan adında çalışır)

Tüm localhost bağlantılarını hedefe çevirir ve doğrular — localhost kalırsa hata verir.
"""
import os, re, sqlite3, sys, urllib.request, shutil

BASE = "http://localhost:8123"
TARGET = sys.argv[1] if len(sys.argv) > 1 else "pages"
if TARGET == "netlify":
    PAGES = ""          # kök-göreli: href="/kurumsal"
    OUTDIR = "dist-netlify"
else:
    PAGES = "https://edis25.github.io/isikceliksanayi"
    OUTDIR = "docs"
ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
os.chdir(ROOT)

routes = ["", "kurumsal", "uretim-teknoloji", "surdurulebilirlik", "urunler", "sektorler",
          "global", "haberler", "iletisim",
          "en/", "en/about-us", "en/production-technology", "en/sustainability", "en/products",
          "en/industries", "en/global-presence", "en/news", "en/contact"]

db = sqlite3.connect("data/site.db")
for (s,) in db.execute("SELECT slug_tr FROM products WHERE is_published=1"): routes.append(f"urunler/{s}")
for (s,) in db.execute("SELECT slug_en FROM products WHERE is_published=1"): routes.append(f"en/products/{s}")
for (s,) in db.execute("SELECT slug_tr FROM news WHERE is_published=1"): routes.append(f"haberler/{s}")
for (s,) in db.execute("SELECT slug_en FROM news WHERE is_published=1"): routes.append(f"en/news/{s}")

def localize(html: bytes) -> bytes:
    # Hangi base ile üretilmiş olursa olsun tüm localhost bağlantılarını Pages'e çevir
    return html.replace(BASE.encode(), PAGES.encode())

shutil.rmtree(OUTDIR, ignore_errors=True)
count = 0
for r in routes:
    rel = r.strip("/")
    out = os.path.join(OUTDIR, rel, "index.html") if rel else f"{OUTDIR}/index.html"
    os.makedirs(os.path.dirname(out) or OUTDIR, exist_ok=True)
    with urllib.request.urlopen(f"{BASE}/{r}") as resp:
        open(out, "wb").write(localize(resp.read()))
    count += 1

try:
    urllib.request.urlopen(f"{BASE}/olmayan-404")
except urllib.error.HTTPError as e:
    open(f"{OUTDIR}/404.html", "wb").write(localize(e.read()))

shutil.copytree("assets", f"{OUTDIR}/assets")
shutil.copy("robots.txt", f"{OUTDIR}/robots.txt")
open(f"{OUTDIR}/.nojekyll", "w").close()

# Doğrulama: hiçbir HTML'de localhost kalmamalı
bad = []
for dirpath, _, files in os.walk(OUTDIR):
    for f in files:
        if f.endswith(".html"):
            p = os.path.join(dirpath, f)
            if b"localhost:8123" in open(p, "rb").read():
                bad.append(p)
if bad:
    print("HATA: localhost bağlantısı kalan dosyalar:", bad[:5])
    sys.exit(1)
print(f"OK: {count} sayfa dökuldü → {OUTDIR} (hedef: {PAGES or 'kök-göreli'})")
