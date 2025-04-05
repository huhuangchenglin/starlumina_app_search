document.addEventListener('DOMContentLoaded', function() {
    // 初始化 Docker 栏功能
    initDock();
    // 初始化
    const searchForm = document.getElementById('search-form');
    const searchInput = document.getElementById('search-input');
    const historyList = document.getElementById('history-list');
    
    // 获取当前搜索引擎设置
    let currentEngine = localStorage.getItem('searchEngine') || 'baidu';
    let darkMode = localStorage.getItem('darkMode') === 'true';
    let showSearchHistory = localStorage.getItem('showSearchHistory') !== 'false'; // 默认显示搜索历史
    
    // 应用深色模式
    if (darkMode) {
        document.documentElement.setAttribute('data-theme', 'dark');
    }
    
    // 根据设置决定是否显示搜索历史区域
    if (!showSearchHistory) {
        document.getElementById('search-history').style.display = 'none';
    }

    // Docker 栏功能初始化
    function initDock() {
        const dockItems = document.querySelectorAll('.dock-item');
        
        dockItems.forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                
                const url = item.getAttribute('data-url');
                const id = item.getAttribute('id');
                
                if (id === 'settings-dock-btn') {
                    window.location.href = 'settings.php';
                } else if (url) {
                    window.location.href = url;
                }
                
                console.log('Clicked:', item.getAttribute('data-name'));
            });
        });

        // 添加调试信息
        console.log('Dock initialized. Total items:', dockItems.length);
    }

    // 移除重复的DOMContentLoaded监听，因为整个代码已经在DOMContentLoaded中
    
    // 添加全局点击事件监听器，用于调试（仅在开发环境中使用）
    if (location.hostname === 'localhost' || location.hostname === '127.0.0.1') {
        document.addEventListener('click', (e) => {
            console.log('Clicked element:', e.target);
        });
    }
    
    // 搜索引擎配置
    const searchEngines = {
        baidu: {
            url: 'https://www.baidu.com/s',
            param: 'wd'
        },
        quark: {
            url: 'https://quark.sm.cn/s',
            param: 'q'
        },
        bing: {
            url: 'https://www.bing.com/search',
            param: 'q'
        },
        google: {
            url: 'https://www.google.com/search',
            param: 'q'
        }
    };
    
    // 加载搜索历史
    function loadSearchHistory() {
        const searchHistoryElement = document.getElementById('search-history');
        
        // 如果设置为不显示搜索历史，则直接返回
        if (!showSearchHistory) {
            searchHistoryElement.style.display = 'none';
            return;
        }
        
        const history = JSON.parse(localStorage.getItem('searchHistory')) || [];
        historyList.innerHTML = '';
        
        if (history.length === 0) {
            searchHistoryElement.style.display = 'none';
            return;
        }
        
        searchHistoryElement.style.display = 'block';
        history.slice(0, 5).forEach(query => {
            const li = document.createElement('li');
            const textSpan = document.createElement('span');
            textSpan.className = 'history-item-text';
            textSpan.textContent = query;
            textSpan.addEventListener('click', () => {
                searchInput.value = query;
                submitSearch(query);
            });
            
            const deleteBtn = document.createElement('button');
            deleteBtn.className = 'delete-history';
            deleteBtn.textContent = '×';
            deleteBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                deleteSearchHistory(query);
            });
            
            li.appendChild(textSpan);
            li.appendChild(deleteBtn);
            historyList.appendChild(li);
        });
        
        // 历史记录显示逻辑已在上面处理
    }

    // 删除单条搜索历史
    function deleteSearchHistory(query) {
        let history = JSON.parse(localStorage.getItem('searchHistory')) || [];
        history = history.filter(item => item !== query);
        localStorage.setItem('searchHistory', JSON.stringify(history));
        loadSearchHistory();
    }

    // 清空所有搜索历史
    function clearSearchHistory() {
        localStorage.removeItem('searchHistory');
        loadSearchHistory();
    }

    // 绑定清空历史按钮事件
    const clearHistoryBtn = document.getElementById('clear-history');
    clearHistoryBtn.addEventListener('click', clearSearchHistory);
    
    // 保存搜索历史
    function saveSearchHistory(query) {
        if (!query.trim()) return;
        
        let history = JSON.parse(localStorage.getItem('searchHistory')) || [];
        // 移除重复的查询
        history = history.filter(item => item !== query);
        // 添加到开头
        history.unshift(query);
        // 保留最近10条
        history = history.slice(0, 10);
        
        localStorage.setItem('searchHistory', JSON.stringify(history));
        loadSearchHistory();
    }
    
    // 检查是否为网址或IP地址
    function isUrlOrIp(input) {
        // 简单的URL检测正则表达式
        const urlRegex = /^(https?:\/\/)?([\\da-z\\.-]+)\\.([a-z\\.]{2,6})([\\/\\w \\.-]*)*\/?$/;
        // IPv4地址检测正则表达式
        const ipv4Regex = /^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;

        return urlRegex.test(input) || ipv4Regex.test(input);
    }

    // 提交搜索
    function submitSearch(query) {
        if (!query.trim()) return;

        // 检查是否为网址或IP地址
        if (isUrlOrIp(query)) {
            // 如果是网址或IP地址，直接跳转
            let url = query;
            if (!url.startsWith('http://') && !url.startsWith('https://')) {
                url = 'http://' + url;
            }
            window.location.href = url;
            return;
        }
        
        const engine = searchEngines[currentEngine];
        searchForm.action = engine.url;
        
        // 创建或更新搜索参数
        let input = searchForm.querySelector(`input[name="${engine.param}"]`);
        if (!input) {
            input = document.createElement('input');
            input.type = 'hidden';
            input.name = engine.param;
            searchForm.appendChild(input);
        }
        input.value = query;
        
        // 保存到历史记录
        saveSearchHistory(query);
        
        // 提交表单
        searchForm.submit();
    }
    
    // 处理表单提交
    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        submitSearch(searchInput.value);
    });
    
    // 初始加载搜索历史
    loadSearchHistory();
    
    // 动画效果 - 搜索框聚焦时的动画
    searchInput.addEventListener('focus', function() {
        this.parentElement.classList.add('focused');
    });
    
    searchInput.addEventListener('blur', function() {
        this.parentElement.classList.remove('focused');
    });
});