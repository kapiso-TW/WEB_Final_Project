# WEB_Final_Project

本專案是一個以 Laravel 開發的排球隊管理系統，提供球員管理、賽事管理、比賽紀錄與球員數據統計等功能。

[README 中文版](./README_tw.md)

[README English](./README.md)

## 開始使用（Getting Started）

本文件將說明如何透過 Docker 建立開發環境並啟動專案。

### 系統需求（Requirements）

在開始執行專案之前，請先確認電腦已安裝以下軟體：

- [Git](https://git-scm.com/)
- [Docker Desktop](https://www.docker.com/products/docker-desktop/)

**注意：** 不需要在本機額外安裝 PHP、Composer、Node.js 或 MySQL。Docker 會透過容器提供並管理這些執行環境。

執行以下指令之前，請先確認 Docker Desktop 已經啟動。

---

## 安裝步驟（Installation）

### 1. 複製專案（Clone the Repository）

使用以下指令，將 GitHub 上的專案複製到本機：

```powershell
git clone https://github.com/kapiso-TW/WEB_Final_Project.git
```

### 2. 進入專案目錄（Enter the Project Directory）

進入專案中的 `main` 資料夾：

```powershell
cd WEB_Final_Project\main
```

**重要：** 後續所有 Docker 指令都必須在 `main` 資料夾中執行，也就是 `compose.yaml` 所在的目錄。

### 3. 設定環境變數（Configure Environment Variables）

Laravel 使用環境變數設定應用程式、資料庫連線及其他服務。

首先，複製範例環境設定檔，建立自己的 `.env`：

```powershell
Copy-Item .env.example .env
```

接著開啟 `.env`，確認以下設定：

```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=volleyball
DB_USERNAME=volleyball_app
DB_PASSWORD=volleyball_password

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

**設定說明：**

- `DB_HOST=db`：代表 MySQL 服務在 Docker Compose 中的服務名稱。
- `DB_PORT=3306`：代表 Docker 容器之間連線時使用的 MySQL 連接埠。
- 資料庫相關設定必須與 `compose.yaml` 中的環境變數一致。
- 請勿將 `.env` 上傳至 GitHub，因為其中可能包含密碼或其他敏感設定。

### 4. 建立並啟動 Docker 服務（Build and Start Docker Services）

使用以下指令，建立所需的 Docker 映像檔並啟動所有服務：

```powershell
docker compose up -d --build
```

第一次啟動時，Docker 會自動執行以下工作：

1. 下載所需的基礎映像檔，例如 PHP、MySQL、Nginx 與 Node.js。
2. 根據 `Dockerfile` 建立 PHP 應用程式映像檔。
3. 安裝 Laravel 所需的 PHP 擴充套件。
4. 建立並啟動 `compose.yaml` 中定義的容器。
5. 建立 Docker 網路，讓不同容器之間可以互相連線。

本專案使用以下服務：

| 服務 | 功能說明 |
|---|---|
| Laravel / PHP-FPM | 執行 Laravel 應用程式並處理 PHP 請求。 |
| Nginx | 作為 Web Server，接收瀏覽器請求並轉交給 PHP-FPM。 |
| MySQL | 儲存應用程式所需的資料。 |
| Node.js / Vite | 安裝前端依賴套件，並編譯 SCSS、JavaScript 等前端資源。 |

### 5. 安裝 PHP 依賴套件（Install PHP Dependencies）

Docker 容器啟動後，使用 Composer 安裝 Laravel 所需的 PHP 套件：

```powershell
docker compose exec app composer install
```

Composer 會讀取 `composer.json` 與 `composer.lock`，並將所需的依賴套件安裝至 `vendor` 資料夾。

### 6. 設定 Laravel 資料夾權限（Configure Laravel Permissions）

Laravel 需要對 `storage` 與 `bootstrap/cache` 資料夾具有寫入權限。

執行以下指令設定資料夾擁有者：

```powershell
docker compose exec -u root app sh -lc "chown -R www-data:www-data storage bootstrap/cache"
```

此指令會將相關資料夾的擁有者設定為 `www-data`，也就是 PHP-FPM 預設使用的執行帳號。

### 7. 產生 Laravel 應用程式金鑰（Generate the Application Key）

使用以下指令，產生 Laravel 所需的應用程式金鑰：

```powershell
docker compose exec -u www-data app php artisan key:generate
```

Laravel 會使用這組金鑰進行資料加密及其他安全相關操作。

**注意：**

- 新環境第一次設定時才需要執行此指令。
- 產生後的 `APP_KEY` 會寫入本機 `.env` 檔案。
- 請妥善保存，不要隨意更換已經使用中的金鑰。

### 8. 執行資料庫遷移（Run Database Migrations）

使用以下指令，建立專案所需的資料表：

```powershell
docker compose exec -u www-data app php artisan migrate
```

Laravel 會執行 `database/migrations` 資料夾中的遷移檔案，並在 MySQL 資料庫中建立對應的資料表。

開發過程中若新增資料庫遷移檔案，也需要再次執行此指令，才能更新資料庫結構。

### 9. 確認 Docker 服務狀態（Check Docker Services）

使用以下指令，確認所有容器是否正常運作：

```powershell
docker compose ps
```

請確認所需的服務都已成功啟動。

若有服務無法正常運作，可以使用以下指令查看所有服務的日誌：

```powershell
docker compose logs -f
```

按下 `Ctrl + C` 可以停止查看日誌，但不會停止 Docker 容器。

### 10. 開啟專案（Open the Application）

當所有服務都正常運作後，開啟瀏覽器並前往：

[http://localhost:8000](http://localhost:8000)

若設定正確，即可看到 Laravel 應用程式。

---

## Docker 常用指令（Docker Commands）

以下整理開發過程中常用的 Docker 指令。

### 啟動系統（Start the System）

啟動所有服務：

```powershell
docker compose up -d
```

Docker 會盡可能沿用已建立的映像檔與容器。

若修改了 `Dockerfile`，或需要重新建立應用程式映像檔，請使用：

```powershell
docker compose up -d --build
```

### 停止系統（Stop the System）

停止並移除 Docker Compose 建立的容器與網路：

```powershell
docker compose down
```

**重要：** 此指令不會刪除專案檔案，也不會刪除儲存在 Docker Volume 中的資料庫資料。

### 暫時停止服務（Temporarily Stop the System）

若只想暫時停止容器，而不移除容器：

```powershell
docker compose stop
```

### 重新啟動服務（Restart Stopped Services）

啟動先前已停止的容器：

```powershell
docker compose start
```

### 查看服務日誌（View Logs）

查看所有服務的日誌：

```powershell
docker compose logs -f
```

查看特定服務的日誌：

```powershell
docker compose logs -f app
```

可以將 `app` 替換成 `nginx`、`db` 或 `node`，查看其他服務的日誌。

### 查看服務狀態（Check Service Status）

```powershell
docker compose ps
```

### 在容器內執行指令（Execute Commands Inside a Container）

可以使用以下指令，在正在執行的容器內執行命令：

```powershell
docker compose exec app <command>
```

例如，查看 PHP 版本：

```powershell
docker compose exec app php -v
```

---

## 注意事項（Important Notes）

- 每位開發者都應建立自己的 `.env` 檔案。
- 請勿將 `.env` 上傳至 GitHub，只需要提交 `.env.example`。
- 除非確定要刪除資料庫及其中的資料，否則請勿執行 `docker compose down -v`。
- 第一次設定環境時，需要建立 Docker 映像檔、安裝 Composer 依賴套件、產生 Laravel 金鑰，以及執行資料庫遷移。
- 從 GitHub 拉取其他成員的更新時，請留意是否新增了依賴套件或資料庫遷移檔案。
- 若 `composer.json` 或 `composer.lock` 有變更，請重新安裝 PHP 依賴套件：

  ```powershell
  docker compose exec app composer install
  ```

- 若新增資料庫遷移檔案，請執行：

  ```powershell
  docker compose exec -u www-data app php artisan migrate
  ```

- 若 `Dockerfile` 有變更，請重新建立應用程式映像檔：

  ```powershell
  docker compose up -d --build
  ```

---

## 專案資訊（Project Information）

**作者：** kapiso  
**日期：** 2026/11/25
**本篇由 chatgpt 生成**