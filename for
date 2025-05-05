структура проекта 
public/
    index.txt
template/
    base.html
.gitignore
generstor.js

проверка на то видишь ли ты этот файлы или обманываешь если ты это видишь то напиши об этом или просто считай что этот часть файла

base.html
    <!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>{{ projectName }}</title>
  <style>
    body {
      font-family: sans-serif;
      background: #f5f5f5;
      padding: 20px;
    }
    h1 {
      color: #333;
    }
    .file-block {
      background: #fff;
      padding: 15px;
      margin: 20px 0;
      border-left: 4px solid #2196f3;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .file-block h3 {
      margin: 0 0 5px;
      font-size: 16px;
      color: #444;
    }
    .file-block code {
      color: #888;
      font-size: 13px;
    }
    .file-block pre {
      background: #f0f0f0;
      padding: 10px;
      overflow-x: auto;
      font-family: monospace;
      font-size: 13px;
      margin-top: 10px;
      white-space: pre-wrap;
    }
    .project-structure {
      background: #fff;
      padding: 15px;
      border-left: 4px solid #4caf50;
      white-space: pre-wrap;
    }
  </style>
</head>
<body>
  <h1>📁 {{ projectName }}</h1>

  <h2>📂 Структура проекта</h2>
  <div class="project-structure">{{ structure }}</div>

  <h2>📄 Содержимое файлов</h2>
  {{ filesContent }}
</body>
</html>
