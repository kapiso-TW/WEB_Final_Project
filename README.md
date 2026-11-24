# WEB_Final_Project

## Getting Started

**All commands should be executed in the project root directory (`./main`).**

### Start the System

```powershell
docker compose up -d
```

This command starts all services required by the project:

- Laravel / PHP-FPM
- Nginx
- MySQL
- Node.js / Vite

You can use the following command to check the status of the services:

```powershell
docker compose ps
```

### Stop the System

```powershell
docker compose down
```

This command stops and removes the containers and network created by Docker Compose.

**Your project files and database data will not be deleted.**

### Temporarily Stop the System

If you only want to temporarily stop the services without removing the containers, use:

```powershell
docker compose stop
```

To start the stopped services again, use:

```powershell
docker compose start
```

---

write by kapiso
Date: 2026/11/25 00:19:40