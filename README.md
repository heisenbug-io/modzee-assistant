# 🤖 Modzee AI-Powered Assistant

This is a full-stack AI assistant module for **Modzee**, built using **Vue 3** (frontend) and **Laravel 12** (backend).  
It uses the **Google Gemini API** to answer user prompts, analyze structured JSON data, and simulate multi-turn conversations.

---

## ✨ Features

- 🔗 Gemini API integration (secure via `.env`)
- 💬 AI-powered Chat Assistant with history
- 📊 Bonus: Detects and summarizes JSON datasets
- 🧠 Multi-turn conversation memory using DB
- 🎨 Clean modern UI built with TailwindCSS
- ⚡ Auto-expanding chat input (like ChatGPT)
- 📄 Markdown-formatted AI responses

---

## 🛠️ Tech Stack

- **Frontend**: Vue 3 + Vite + TailwindCSS
- **Backend**: Laravel 12 (API only)
- **AI**: Google Gemini API (v1beta)
- **Database**: SQLite (via Laravel Eloquent)
- **Styling**: TailwindCSS + `marked` for Markdown parsing

---

## 🚀 Quick Start

### 1. Clone the Repo

```bash
git clone https://github.com/heisenbug-io/modzee-assistant.git
cd modzee-assistant
```

---

### 2. Backend Setup (Laravel 12)

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
```

#### Set your Google API key in `.env`:
```
GEMINI_API_KEY=your_actual_gemini_api_key
```

```bash
php artisan migrate
php artisan serve
```

✅ This runs your Laravel API at [http://localhost:8000](http://localhost:8000)

---

### 3. Frontend Setup (Vue 3)

```bash
cd ../frontend
npm install
npm run dev
```

✅ This runs your frontend app at [http://localhost:5173](http://localhost:5173)

---

## 📡 API Endpoints

| Method | Endpoint                   | Description                       |
|--------|----------------------------|-----------------------------------|
| POST   | `/api/ai/assistant`        | Send a user prompt to Gemini      |
| GET    | `/api/ai/logs`             | Fetch chat history                |
| POST   | `/api/ai/reset`            | Clear chat conversation history   |

---

## 🤓 How It Works

- Each user message is stored in the `ai_logs` table
- All prior messages are sent with every new prompt
- JSON data in prompt? ➡️ Automatically formatted for Gemini summarization
- Gemini replies are markdown-formatted and rendered beautifully in the UI

---

## 🔒 Security Notes

- API key is stored in `.env` and never exposed on the frontend
- CORS is configured for local development (`localhost:5173`)
- Only the backend calls Gemini via `Http::withHeaders()`

---

## 🧠 Credits

Built with ❤️ by Pujan Bhuva (@heisenbug-io) as part of an AI integration skills test for Modzee.

---

## 📄 License

MIT License
