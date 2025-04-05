<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Starlumina Search</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1 class="brand">星芒浏览器</h1>
        </header>
        <main>
            <div class="search-container">
                <form id="search-form" action="" method="GET">
                    <div class="search-box">
                        <input type="text" id="search-input" placeholder="搜索或输入网址" autocomplete="off">
                        <button type="submit" class="search-btn">
                            <svg viewBox="0 0 24 24" width="24" height="24">
                                <path d="M15.5 14h-.79l-.28-.27a6.5 6.5 0 0 0 1.48-5.34c-.47-2.78-2.79-5-5.59-5.34a6.505 6.505 0 0 0-7.27 7.27c.34 2.8 2.56 5.12 5.34 5.59a6.5 6.5 0 0 0 5.34-1.48l.27.28v.79l4.25 4.25c.41.41 1.08.41 1.49 0 .41-.41.41-1.08 0-1.49L15.5 14zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
            <div class="search-history" id="search-history">
                <div class="search-history-header">
                    <h3>搜索历史</h3>
                    <button class="clear-history" id="clear-history">清空历史</button>
                </div>
                <ul id="history-list"></ul>
            </div>
        </main>
        <div class="dock-container">
            <div class="dock">
                <!-- 设置按钮 -->
                <button class="dock-item" data-name="设置" id="settings-dock-btn">
                    <svg viewBox="0 0 24 24" width="24" height="24">
                        <path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.07.62-.07.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/>
                    </svg>
                    <span class="dock-item-name">设置</span>
                </button>
                <!-- 星芒博客 -->
                <button class="dock-item" data-name="星芒博客" data-url="https://blog.starlumina.com/">
                    <svg viewBox="0 0 24 24" width="24" height="24">
                        <path d="M19 5v14H5V5h14m0-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                    </svg>
                    <span class="dock-item-name">星芒博客</span>
                </button>
                <!-- 星芒工具箱 -->
                <button class="dock-item" data-name="星芒工具箱" data-url="https://tool.starlumina.com/">
                    <svg viewBox="0 0 24 24" width="24" height="24">
                        <path d="M22.7 19l-9.1-9.1c.9-2.3.4-5-1.5-6.9-2-2-5-2.4-7.4-1.3L9 6 6 9 1.6 4.7C.4 7.1.9 10.1 2.9 12.1c1.9 1.9 4.6 2.4 6.9 1.5l9.1 9.1c.4.4 1 .4 1.4 0l2.3-2.3c.5-.4.5-1.1.1-1.4z"/>
                    </svg>
                    <span class="dock-item-name">星芒工具箱</span>
                </button>
                <!-- 星芒下载站 -->
                <button class="dock-item" data-name="星芒下载站" data-url="https://pan.starlumina.com/">
                    <svg viewBox="0 0 24 24" width="24" height="24">
                        <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/>
                    </svg>
                    <span class="dock-item-name">星芒下载站</span>
                </button>
            </div>
        </div>
    </div>
    <script src="assets/js/main.js"></script>
</body>
</html>