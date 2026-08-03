@echo off
echo ========================================================
echo Memulai Server AI & Logistik Donat Menak (FastAPI)...
echo ========================================================
cd /d "%~dp0"
venv\Scripts\uvicorn.exe main:app --reload --port 8002 --host 127.0.0.1
pause
