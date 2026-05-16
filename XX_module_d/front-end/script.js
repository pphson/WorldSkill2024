/** * BIẾN QUAN TRỌNG: Điều chỉnh đường dẫn API tại đây. 
 * Nếu cấu hình host là wsXX.worldskills.org, đổi lại cho phù hợp.
 */
const API_BASE_URL = '../module_d_api.php';

// --- State Management ---
const state = {
    currentView: 'view-carparks',
    theme: localStorage.getItem('theme') || 'system',
    sortCarpark: localStorage.getItem('sortCarpark') || 'distance',
    pinnedCarparks: JSON.parse(localStorage.getItem('pinnedCarparks')) || [],
    userLat: 45.755051, // Default Lyon
    userLng: 4.846358,
    eventsNextPageUrl: null,
    isFetchingEvents: false
};

// --- Initialization ---
document.addEventListener('DOMContentLoaded', () => {
    initTheme();
    setupNavigation();
    setupSettings();
    getUserLocation();
    
    // Load initial view data
    loadCarparks();
});

// --- Utility Functions ---
// Haversine formula to calculate distance between two lat/lng points
function getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) {
    const R = 6371; // Radius of the earth in km
    const dLat = deg2rad(lat2 - lat1);
    const dLon = deg2rad(lon2 - lon1); 
    const a = 
      Math.sin(dLat/2) * Math.sin(dLat/2) +
      Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * Math.sin(dLon/2) * Math.sin(dLon/2); 
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
    return (R * c).toFixed(2); // return string with 2 decimals
}
function deg2rad(deg) { return deg * (Math.PI/180); }

// Geolocation
function getUserLocation() {
    // Check URL parameters first for manual simulation
    const urlParams = new URLSearchParams(window.location.search);
    if(urlParams.has('latitude') && urlParams.has('longitude')) {
        state.userLat = parseFloat(urlParams.get('latitude'));
        state.userLng = parseFloat(urlParams.get('longitude'));
        return;
    }

    // Use browser geolocation as default
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                state.userLat = position.coords.latitude;
                state.userLng = position.coords.longitude;
                if(state.currentView === 'view-carparks') loadCarparks();
            },
            (error) => { console.warn("Geolocation blocked/failed, using default Lyon coordinates."); }
        );
    }
}

// --- Navigation & Routing ---
function setupNavigation() {
    document.querySelectorAll('.nav-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            // UI update
            document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('active'));
            e.target.classList.add('active');
            
            // View update
            const targetView = e.target.getAttribute('data-target');
            document.querySelectorAll('.view').forEach(v => v.classList.remove('active'));
            document.getElementById(targetView).classList.add('active');
            
            state.currentView = targetView;
            
            // Update Header Title
            document.getElementById('header-title').innerText = e.target.innerText;
            document.getElementById('btn-back').style.display = 'none';

            // Trigger data load
            if (targetView === 'view-carparks') loadCarparks();
            if (targetView === 'view-events') {
                document.getElementById('event-list').innerHTML = '';
                loadEvents(`${API_BASE_URL}/events.json`);
            }
            if (targetView === 'view-weather') loadWeather();
        });
    });

    document.getElementById('btn-back').addEventListener('click', () => {
        if(state.currentView === 'view-carparks') {
            document.getElementById('carpark-detail').style.display = 'none';
            document.getElementById('carpark-list').style.display = 'block';
            document.getElementById('btn-back').style.display = 'none';
            document.getElementById('header-title').innerText = 'Carparks';
        }
    });
}

// --- Views Logic ---

// 1. Carparks
async function loadCarparks() {
    document.getElementById('carpark-detail').style.display = 'none';
    document.getElementById('carpark-list').style.display = 'block';

    try {
        const response = await fetch(`${API_BASE_URL}/carparks.json`);
        const data = await response.json();
        
        let carparksArray = Object.keys(data).map(key => ({
            name: key,
            ...data[key],
            distance: parseFloat(getDistanceFromLatLonInKm(state.userLat, state.userLng, data[key].latitude, data[key].longitude)),
            isPinned: state.pinnedCarparks.includes(key)
        }));

        // Sorting
        carparksArray.sort((a, b) => {
            // Pinned always on top
            if (a.isPinned && !b.isPinned) return -1;
            if (!a.isPinned && b.isPinned) return 1;

            if (state.sortCarpark === 'distance') {
                return a.distance - b.distance;
            } else {
                return a.name.localeCompare(b.name);
            }
        });

        renderCarparksList(carparksArray);
    } catch (error) {
        console.error("Failed to load carparks", error);
    }
}

function renderCarparksList(carparks) {
    const listEl = document.getElementById('carpark-list');
    listEl.innerHTML = '';

    carparks.forEach(cp => {
        const item = document.createElement('div');
        item.className = 'carpark-item';
        item.innerHTML = `
            <div class="carpark-info" style="cursor:pointer; flex-grow:1;">
                <h3>${cp.isPinned ? ' ' : ''}${cp.name}</h3>
                <p>Available: ${cp.availableSpaces} | Dist: ${cp.distance} km</p>
            </div>
            <button class="btn-pin">${cp.isPinned ? 'Unpin' : 'Pin'}</button>
        `;

        // Focus View
        item.querySelector('.carpark-info').addEventListener('click', () => {
            document.getElementById('carpark-list').style.display = 'none';
            const detail = document.getElementById('carpark-detail');
            detail.style.display = 'block';
            
            document.getElementById('detail-name').innerText = cp.name;
            document.getElementById('detail-distance').innerText = cp.distance;
            document.getElementById('detail-available').innerText = cp.availableSpaces;
            
            document.getElementById('header-title').innerText = 'Carpark Detail';
            document.getElementById('btn-back').style.display = 'block';
        });

        // Pinning logic
        item.querySelector('.btn-pin').addEventListener('click', (e) => {
            e.stopPropagation();
            if (cp.isPinned) {
                state.pinnedCarparks = state.pinnedCarparks.filter(name => name !== cp.name);
            } else {
                state.pinnedCarparks.push(cp.name);
            }
            localStorage.setItem('pinnedCarparks', JSON.stringify(state.pinnedCarparks));
            loadCarparks(); // Reload list to reflect sort
        });

        listEl.appendChild(item);
    });
}

// 2. Events (Infinite Scroll & Filter)
async function loadEvents(url, append = false) {
    if (state.isFetchingEvents) return;
    state.isFetchingEvents = true;
    
    if(!append) document.getElementById('event-list').innerHTML = '';
    document.getElementById('event-loader').style.display = 'block';

    try {
        const response = await fetch(url);
        const data = await response.json();
        
        const listEl = document.getElementById('event-list');
        data.events.forEach(event => {
            const item = document.createElement('div');
            item.className = 'event-item';
            // Need to prefix image URL if it's relative from API
            const imgUrl = event.image.startsWith('/') ? event.image : `/${event.image}`;
            item.innerHTML = `
                <img src="${imgUrl}" alt="${event.title}" loading="lazy">
                <div class="info">
                    <h4>${event.title}</h4>
                    <p>Date: ${event.date}</p>
                </div>
            `;
            listEl.appendChild(item);
        });

        state.eventsNextPageUrl = data.pages?.next;
        setupInfiniteScroll();

    } catch (error) {
        console.error("Failed to load events", error);
    } finally {
        state.isFetchingEvents = false;
        document.getElementById('event-loader').style.display = 'none';
    }
}

// Infinite Scroll Observer
function setupInfiniteScroll() {
    const observer = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting && state.eventsNextPageUrl) {
            // Add slight delay to prevent eager loading
            setTimeout(() => {
                // Determine base path correctly for pagination URL
                const nextUrl = state.eventsNextPageUrl.startsWith('http') ? 
                                state.eventsNextPageUrl : 
                                `${window.location.origin}${state.eventsNextPageUrl}`;
                loadEvents(nextUrl, true);
            }, 300);
        }
    }, { rootMargin: '100px' }); // Load slightly before reaching bottom

    const loader = document.getElementById('event-loader');
    observer.disconnect(); // Reset previous observer
    if (state.eventsNextPageUrl) {
        observer.observe(loader);
    }
}

// Event Filter
document.getElementById('btn-filter-event').addEventListener('click', () => {
    const start = document.getElementById('filter-start').value;
    const end = document.getElementById('filter-end').value;
    
    let url = `${API_BASE_URL}/events.json?`;
    if(start) url += `beginning_date=${start}&`;
    if(end) url += `ending_date=${end}`;
    
    loadEvents(url);
});

// 3. Weather
async function loadWeather() {
    const container = document.getElementById('weather-scroll-container');
    container.innerHTML = 'Loading...';

    try {
        // 1. Lấy dữ liệu thời tiết
        const response = await fetch(`${API_BASE_URL}/weather.json`);
        const data = await response.json();
        
        container.innerHTML = '';
        
        // Dùng vòng lặp for...of để có thể sử dụng await bên trong
        for (const day of data) {
            const card = document.createElement('div');
            card.className = 'weather-card';
            
            // 2. Chuyển đổi trạng thái thành tên file (VD: 'Cloudy' -> 'cloudy.svg')
            const fileName = day.status.toLowerCase() + '.svg';
            // LƯU Ý: Chỉnh sửa lại đường dẫn thư mục này cho khớp với cấu trúc máy bạn
            const iconUrl = `../svn icons/${fileName}`; 
            
            let svgContent = '';
            try {
                // 3. Đọc nội dung file SVG có sẵn trong thư mục
                const svgResponse = await fetch(iconUrl);
                if (svgResponse.ok) {
                    svgContent = await svgResponse.text();
                } else {
                    svgContent = `<span>Icon lỗi</span>`; // Fallback nếu không tìm thấy file
                }
            } catch (err) {
                console.error("Lỗi tải icon:", iconUrl);
            }

            // 4. Đổ dữ liệu ra màn hình
            card.innerHTML = `
                <h4>${day.date}</h4>
                <div class="weather-icon-wrapper">
                    ${svgContent}
                </div>
                <p>${day.lower_temperature} - ${day.upper_temperature}°C</p>
                <p>${day.status}</p>
            `;
            container.appendChild(card);
        }
    } catch (error) {
        console.error("Failed to load weather", error);
    }
}

// 4. Settings & Theme
function initTheme() {
    document.getElementById('theme-select').value = state.theme;
    document.getElementById('sort-select').value = state.sortCarpark;
    applyTheme(state.theme);

    // Watch system theme changes
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
        if(state.theme === 'system') applyTheme('system');
    });
}

function applyTheme(themeValue) {
    let activeTheme = themeValue;
    if (themeValue === 'system') {
        activeTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }
    document.documentElement.setAttribute('data-theme', activeTheme);
}

function setupSettings() {
    document.getElementById('theme-select').addEventListener('change', (e) => {
        state.theme = e.target.value;
        localStorage.setItem('theme', state.theme);
        applyTheme(state.theme);
    });

    document.getElementById('sort-select').addEventListener('change', (e) => {
        state.sortCarpark = e.target.value;
        localStorage.setItem('sortCarpark', state.sortCarpark);
        // Will apply next time Carpark view is loaded
    });
}