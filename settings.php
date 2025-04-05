<?php
$config = require 'config.php';
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>设置 - Starlumina Search</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .settings-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .settings-group {
            margin-bottom: 24px;
            background: var(--search-bg);
            border-radius: 12px;
            padding: 16px;
        }
        
        .settings-group h2 {
            font-size: 1.2rem;
            margin-bottom: 16px;
            color: var(--text-color);
        }
        
        .engine-options {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
        }
        
        .engine-option {
            background: var(--background-color);
            border: 2px solid var(--primary-color);
            border-radius: 8px;
            padding: 12px;
            cursor: pointer;
            text-align: center;
            color: var(--text-color);
            transition: all 0.2s;
        }
        
        .engine-option.active {
            background: var(--primary-color);
            color: white;
        }
        
        .theme-switch {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px;
            background: var(--background-color);
            border-radius: 8px;
        }
        
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }
        
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }
        
        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        
        input:checked + .slider {
            background-color: var(--primary-color);
        }
        
        input:checked + .slider:before {
            transform: translateX(26px);
        }
        
        .back-btn {
            display: inline-flex;
            align-items: center;
            padding: 8px 16px;
            color: var(--text-color);
            text-decoration: none;
            border-radius: 20px;
            transition: background-color 0.2s;
        }
        
        .back-btn:hover {
            background-color: var(--suggestion-hover);
        }
        
        .back-btn svg {
            margin-right: 8px;
            fill: var(--text-color);
        }

        @media (max-width: 768px) {
            .settings-container {
                max-width: 600px;
            }
            .engine-options {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .settings-container {
                max-width: 100%;
                padding: 10px;
            }
            .engine-options {
                grid-template-columns: 1fr;
            }
            .engine-option {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container settings-container">
        <button class="settings-back-btn" onclick="window.location.href='index.php'">
            <svg viewBox="0 0 24 24" width="24" height="24">
                <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
            </svg>
        </button>
        <header>
            <h1 class="brand">设置</h1>
        </header>
        <div class="settings-content">
            <div class="settings-group">
                <h2>搜索引擎</h2>
                <div class="engine-options">
                    <div class="engine-option" data-engine="baidu">百度</div>
                    <div class="engine-option" data-engine="quark">夸克</div>
                    <div class="engine-option" data-engine="bing">必应</div>
                    <div class="engine-option" data-engine="google">谷歌</div>
                </div>
            </div>
            <div class="settings-group">
                <h2>深色模式</h2>
                <div class="theme-switch">
                    <span>开启深色模式</span>
                    <label class="switch">
                        <input type="checkbox" id="theme-toggle">
                        <span class="slider"></span>
                    </label>
                </div>
            </div>
            <div class="settings-group">
                <h2>搜索历史</h2>
                <div class="theme-switch">
                    <span>显示搜索历史</span>
                    <label class="switch">
                        <input type="checkbox" id="history-toggle">
                        <span class="slider"></span>
                    </label>
                </div>
            </div>
        </div>
        
        <!-- 添加版本号和备案信息 -->
        <div class="footer-info">
            <div class="version-info" id="version-info">版本号: <?php echo $config['version']; ?></div>
            <div class="beian-info">
                <a href="https://beian.miit.gov.cn/" target="_blank">蜀ICP备2024095899号-3</a><br>
                <a href="https://beian.mps.gov.cn/#/query/webSearch?code=51019002007728" target="_blank">
                    <img class="logos" src="https://ico.starlumina.com/备案图标.png" width="15" height="15">
                    川公网安备51019002007728号
                </a>
            </div>
        </div>
    </div>
    
    <!-- 版本更新内容弹窗 -->
    <div id="version-modal" class="version-modal">
        <div class="version-modal-content">
            <div class="version-modal-header">
                <h3>版本更新内容</h3>
                <button class="version-modal-close">&times;</button>
            </div>
            <div class="version-modal-body">
                <h4>v<?php echo $config['version']; ?> (<?php echo $config['update_time']; ?>)</h4>
                <ul>
                    <?php foreach ($config['update_notes'][$config['version']] as $note): ?>
                        <li><?php echo $note; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const engineOptions = document.querySelectorAll('.engine-option');
            const themeToggle = document.getElementById('theme-toggle');
            
            // 加载当前设置
            const currentEngine = localStorage.getItem('searchEngine') || 'baidu';
            const darkMode = localStorage.getItem('darkMode') === 'true';
            
            // 设置搜索引擎选中状态
            engineOptions.forEach(option => {
                if (option.dataset.engine === currentEngine) {
                    option.classList.add('active');
                }
                
                option.addEventListener('click', function() {
                    engineOptions.forEach(opt => opt.classList.remove('active'));
                    this.classList.add('active');
                    localStorage.setItem('searchEngine', this.dataset.engine);
                    // 添加提示信息
                    alert('搜索引擎已切换为: ' + this.textContent);
                });
            });
            
            // 设置深色模式状态
            themeToggle.checked = darkMode;
            if (darkMode) {
                document.documentElement.setAttribute('data-theme', 'dark');
            }
            
            // 处理深色模式切换
            themeToggle.addEventListener('change', function() {
                if (this.checked) {
                    document.documentElement.setAttribute('data-theme', 'dark');
                    localStorage.setItem('darkMode', 'true');
                } else {
                    document.documentElement.setAttribute('data-theme', 'light');
                    localStorage.setItem('darkMode', 'false');
                }
            });

            // 处理搜索历史显示切换
            const historyToggle = document.getElementById('history-toggle');
            const showHistory = localStorage.getItem('showSearchHistory') !== 'false';
            
            historyToggle.checked = showHistory;
            
            historyToggle.addEventListener('change', function() {
                localStorage.setItem('showSearchHistory', this.checked);
            });
            
            // 版本信息弹窗功能
            const versionInfo = document.getElementById('version-info');
            const versionModal = document.getElementById('version-modal');
            const versionModalClose = document.querySelector('.version-modal-close');
            
            versionInfo.addEventListener('click', function() {
                versionModal.style.display = 'flex';
            });
            
            versionModalClose.addEventListener('click', function() {
                versionModal.style.display = 'none';
            });
            
            // 点击弹窗外部关闭
            window.addEventListener('click', function(event) {
                if (event.target === versionModal) {
                    versionModal.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
```
