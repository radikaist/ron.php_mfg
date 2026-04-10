RoadMap Pengerjaan saat ini
1. Rancang arsitektur framework
2. Buat struktur folder = Onprogress 2026/04/10
3. Buat kernel MVC
4. Buat konfigurasi database
5. Buat sistem auth
6. Buat Dynamic RBAC
7. Integrasi AdminLTE
8. Buat modul manufaktur

Untuk framework mandiri ini, arsitektur sederhananya:
- public/ → entry point web
- app/Controllers/ → controller
- app/Models/ → model
- app/Views/ → view
- app/Middleware/ → auth, role, permission
- core/ → kernel framework
- config/ → konfigurasi app/database/rbac
- routes/ → route list
- storage/ → logs, cache, uploads
- vendor/ → opsional kalau nanti pakai composer
- database/ → schema dan seed
