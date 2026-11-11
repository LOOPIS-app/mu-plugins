# 🎯 LOOPIS mu-plugins
**Mini plugins loading from the folder 'mu-plugins' of LOOPIS.app**

## 💡 Status
Plugins placed here run early and cannot be deactivated by accident.

## 🖼️ Image uploads
JPEG, PNG, WebP, and supported HEIC/HEIF uploads are converted to WebP at quality 70 before attachment creation; full-size files are capped to the configured `large_size_w` and `large_size_h` options (1600×1600 on LOOPIS sites). WebP encoding support is required from the server's active image editor. WordPress generates only the configured thumbnail size (240×240 on LOOPIS sites), avoiding duplicate large and scaled files. Other formats use WordPress defaults.

## 📋 Changelog
See [CHANGELOG.md](CHANGELOG.md)

## 💾 Installation
Installation & updates are managed by plugin "LOOPIS Config".

## 👤 Contributors
Johan Hagvil, Hubert Hilborn