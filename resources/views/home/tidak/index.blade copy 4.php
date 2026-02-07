<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Archive | Museum Mpu Tantular</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #fdfdfd;
            color: #1e293b;
        }

        /* Hero & Glassmorphism */
        .glass-nav {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        /* Modern Card Styling */
        .manuskrip-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(226, 232, 240, 0.6);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.01);
        }

        .manuskrip-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
        }

        .manuskrip-card img {
            width: 100%;
            height: 280px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .manuskrip-card:hover img {
            transform: scale(1.05);
        }

        /* Pill Buttons */
        .cat-btn {
            padding: 10px 24px;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.3s;
            background: #f1f5f9;
            color: #64748b;
        }

        .cat-btn.active {
            background: #0f172a;
            color: white;
            box-shadow: 0 10px 15px -3px rgba(15, 23, 42, 0.3);
        }

        /* Modal styling */
        .modal-gradient {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }

        .info-row {
            display: flex;
            flex-direction: column;
            gap: 4px;
            padding: 16px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }

        /* navbar */
        /* Styling Navbar Link */
.nav-link {
    padding: 20px 0;
    font-size: 12px;
    font-weight: 800;
    color: #64748b;
    letter-spacing: 0.1em;
    border-bottom: 3px solid transparent;
    transition: all 0.3s;
    white-space: nowrap;
    display: flex;
    align-items: center;
}

.nav-link.active {
    color: #4f46e5;
    border-bottom-color: #4f46e5;
}

.sub-nav-btn {
    padding: 8px 16px;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    font-size: 11px;
    font-weight: 700;
    color: #475569;
    transition: all 0.2s;
    white-space: nowrap;
}

.sub-nav-btn.active {
    background: #4f46e5;
    color: white;
    border-color: #4f46e5;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
}

.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="min-h-screen">

    <header class="glass-nav border-b border-gray-100 py-5 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-200">
                    <i class="fa-solid fa-scroll text-lg"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight leading-none">Mpu Tantular</h1>
                    <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-[0.2em]">Digital Archive</span>
                </div>
            </div>
            <div id="clock" class="hidden md:block px-4 py-2 bg-slate-50 rounded-lg font-mono text-sm font-semibold text-slate-500">
                00:00:00
            </div>
        </div>
    </header>
    <nav class="sticky top-[80px] z-30 bg-white border-b border-slate-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex gap-8 overflow-x-auto no-scrollbar py-1" id="parentNav">
            <button onclick="resetToHome()" id="btn-all" class="nav-link active">
                <i class="fa-solid fa-house-chimney mr-2 text-xs"></i> SEMUA
            </button>
            @foreach($categories->where('parent_id', null) as $cat)
                <button onclick="handleParentClick({{ $cat->id }}, '{{ $cat->category_name }}')" id="parent-{{ $cat->id }}" class="nav-link">
                    {{ strtoupper($cat->category_name) }}
                </button>
            @endforeach
        </div>
    </div>

    <div id="subNavbar" class="bg-slate-50 border-t border-slate-100 hidden animate-in slide-in-from-top-2 duration-300">
        <div class="max-w-7xl mx-auto px-6 flex gap-4 py-3 overflow-x-auto no-scrollbar" id="childNav">
            </div>
    </div>
</nav>

<div class="max-w-7xl mx-auto px-6 pt-6">
    <div id="breadcrumb" class="hidden flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
        <span>Koleksi</span>
        <i class="fa-solid fa-chevron-right text-[8px]"></i>
        <span id="bc-parent" class="text-indigo-600"></span>
        <span id="bc-separator" class="hidden"><i class="fa-solid fa-chevron-right text-[8px]"></i></span>
        <span id="bc-child" class="hidden text-indigo-400"></span>
    </div>
</div>

    <main class="max-w-7xl mx-auto px-6 py-12">

        <div class="mb-12">
            <h2 class="text-4xl font-extrabold text-slate-900 mb-2">Eksplorasi Manuskrip</h2>
            <p class="text-slate-500 max-w-2xl">Menelusuri jejak sejarah melalui digitalisasi naskah kuno koleksi Museum Mpu Tantular Jawa Timur.</p>
        </div>

        {{-- <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-12"> --}}
            {{-- <div class="flex flex-wrap gap-3" id="categoryFilters">
                <button onclick="filterCategory('All')" class="cat-btn active">Semua</button>
                @foreach($categories as $cat)
                    <button onclick="filterCategory('{{ $cat->category_name }}')" class="cat-btn">
                        {{ $cat->category_name }}
                    </button>
                @endforeach
            </div> --}}
            {{-- <div class="flex flex-wrap gap-3" id="categoryFilters">
        <button onclick="filterCategory('All')" class="cat-btn active" data-id="all">Semua</button>
        @foreach($categories->where('parent_id', null) as $cat)
            <button onclick="handleCategoryClick({{ $cat->id }}, '{{ $cat->category_name }}')" class="cat-btn">
                {{ $cat->category_name }}
            </button>
        @endforeach --}}

    {{-- </div> --}}

            <div class="relative group">
                <input type="text" id="searchInput" onkeyup="searchBooks()"
                    placeholder="Cari judul atau kode..."
                    class="w-full md:w-80 pl-12 pr-6 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
            </div>
        </div>

        <div id="bookGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            </div>
    </main>

    <div id="bookModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-0 md:p-10 bg-slate-900/95 backdrop-blur-md">
        <div class="bg-white w-full max-w-6xl h-full md:h-[85vh] flex flex-col md:flex-row shadow-2xl overflow-hidden md:rounded-[32px] relative animate-in fade-in zoom-in duration-300">

            <button onclick="closeModal()" class="absolute top-4 right-4 z-50 md:hidden bg-white/80 backdrop-blur-md w-10 h-10 rounded-full flex items-center justify-center text-slate-800 shadow-lg">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <div class="w-full md:w-1/2 modal-gradient p-6 md:p-12 flex items-center justify-center relative min-h-[300px] md:min-h-full">
                <img id="modalCover" src="" class="h-48 md:h-auto md:max-h-full rounded-lg shadow-[0_20px_50px_rgba(0,0,0,0.2)] transform md:-rotate-2 hover:rotate-0 transition-transform duration-500 object-contain">
            </div>

            <div class="w-full md:w-1/2 flex flex-col bg-white overflow-hidden">
                <div class="p-8 md:p-12 pb-0 flex justify-between items-start">
                    <div class="w-full">
                        <span id="modalTag" class="px-3 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-extrabold rounded-full uppercase tracking-wider mb-3 inline-block"></span>
                        <h2 id="modalTitle" class="text-2xl md:text-4xl font-extrabold text-slate-900 leading-tight"></h2>
                    </div>
                    <button onclick="closeModal()" class="hidden md:block text-slate-300 hover:text-rose-500 text-3xl transition-colors">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                </div>

                <div class="flex-grow overflow-y-auto p-8 md:p-12 pt-4 custom-scroll">
                    <div class="grid grid-cols-2 gap-4 mb-8">
                        <div class="info-row border-b-0 bg-slate-50 p-4 rounded-2xl">
                            <span class="font-bold text-slate-400 text-[10px] uppercase tracking-widest">Kode Naskah</span>
                            <span id="modalCode" class="text-slate-900 font-bold text-base md:text-lg"></span>
                        </div>
                        <div class="info-row border-b-0 bg-slate-50 p-4 rounded-2xl">
                            <span class="font-bold text-slate-400 text-[10px] uppercase tracking-widest">Penulis</span>
                            <span id="modalAuthor" class="text-slate-900 font-semibold text-base md:text-lg"></span>
                        </div>
                    </div>

                    <div class="mb-8">
                        <span class="font-bold text-slate-400 text-[10px] uppercase tracking-widest mb-2 block font-mono">Ringkasan Manuskrip</span>
                        <p id="modalDesc" class="text-slate-600 leading-relaxed text-sm md:text-base text-justify italic"></p>
                    </div>
                </div>

                <div class="p-8 md:p-12 pt-4 bg-white border-t border-slate-100">
                    <button onclick="openPDF()" class="w-full py-4 md:py-5 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 hover:shadow-xl hover:shadow-indigo-200 transition-all flex items-center justify-center gap-3 active:scale-[0.98]">
                        <i class="fa-solid fa-book-open"></i>
                        BACA DIGITALISASI LENGKAP
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="pdfViewer" class="fixed inset-0 z-[60] hidden bg-slate-950 flex flex-col">
        <div class="bg-slate-900/50 backdrop-blur-md p-4 flex justify-between items-center text-white border-b border-white/10">
            <div class="flex items-center gap-4">
                <i class="fa-solid fa-file-pdf text-rose-500 text-2xl"></i>
                <h3 id="pdfTitle" class="font-bold text-sm md:text-base uppercase tracking-widest opacity-80">Membaca Manuskrip</h3>
            </div>
            <button onclick="closePDF()" class="bg-rose-500 hover:bg-rose-600 px-6 py-2 rounded-xl font-bold transition-all shadow-lg shadow-rose-500/20 text-sm">
                TUTUP PANEL
            </button>
        </div>
        <div class="flex-grow w-full h-full bg-slate-800">
            <iframe id="pdfFrame" src="" class="w-full h-full border-none"></iframe>
        </div>
    </div>

    <script>
    // 1. DATA INITIALIZATION
    // Mengambil data dari Laravel ke JavaScript
    const allCategories = @json($categories);
    const books = @json($books).map(book => ({
        id: book.id,
        category_id: book.category_id,
        category_name: book.category ? book.category.category_name : 'Koleksi',
        title: book.title,
        code: book.manuscript_code || "MS-" + book.id,
        author: book.author || 'Anonim',
        desc: book.description || 'Tidak ada deskripsi tersedia.',
        cover: `/storage/${book.cover_image}`,
        pdf: `/storage/${book.pdf_file}`
    }));

    let currentActiveId = null;
    let activeParentId = null;

    // 2. LOGIKA NAVBAR & KATEGORI
    function resetToHome() {
        activeParentId = null;
        updateActiveState('btn-all');
        document.getElementById('subNavbar').classList.add('hidden');
        document.getElementById('breadcrumb').classList.add('hidden');
        displayBooks(books);
    }

    function handleParentClick(id, name) {
        activeParentId = id;
        updateActiveState(`parent-${id}`);

        // Cari sub-kategori (Anak)
        const children = allCategories.filter(c => c.parent_id === id);
        const subNav = document.getElementById('subNavbar');
        const childContainer = document.getElementById('childNav');

        if (children.length > 0) {
            subNav.classList.remove('hidden');
            childContainer.innerHTML = children.map(c => `
                <button onclick="handleChildClick(${c.id}, '${c.category_name}')" class="sub-nav-btn" id="child-${c.id}">
                    ${c.category_name.toUpperCase()}
                </button>
            `).join('');
        } else {
            subNav.classList.add('hidden');
        }

        showBreadcrumb(name);
        filterByCategoryId(id);
    }

    function handleChildClick(childId, childName) {
        updateActiveState(`child-${childId}`, true);

        // Update Breadcrumb Level 2
        document.getElementById('bc-separator').classList.remove('hidden');
        document.getElementById('bc-child').classList.remove('hidden');
        document.getElementById('bc-child').innerText = childName;

        filterByCategoryId(childId);
    }

    // 3. LOGIKA FILTERING & PENCARIAN
    function filterByCategoryId(id) {
        // Mengambil buku di kategori ini + semua buku di sub-kategorinya (deep filter)
        const childIds = getRecursiveChildIds(id);
        const targetIds = [id, ...childIds];

        const filtered = books.filter(b => targetIds.includes(b.category_id));
        displayBooks(filtered);
    }

    function getRecursiveChildIds(parentId) {
        let ids = [];
        const children = allCategories.filter(c => c.parent_id === parentId);
        children.forEach(c => {
            ids.push(c.id);
            ids = [...ids, ...getRecursiveChildIds(c.id)];
        });
        return ids;
    }

    function searchBooks() {
        const keyword = document.getElementById('searchInput').value.toLowerCase();
        const filtered = books.filter(b =>
            b.title.toLowerCase().includes(keyword) ||
            b.code.toLowerCase().includes(keyword) ||
            b.author.toLowerCase().includes(keyword)
        );
        displayBooks(filtered);
    }

    // 4. DISPLAY & MODAL
    function displayBooks(data) {
        const grid = document.getElementById('bookGrid');
        if (data.length === 0) {
            grid.innerHTML = `<div class="col-span-full py-20 text-center text-slate-400">Tidak ada koleksi ditemukan.</div>`;
            return;
        }
        grid.innerHTML = data.map(book => `
            <div onclick="showDetail(${book.id})" class="manuskrip-card cursor-pointer group">
                <div class="overflow-hidden relative">
                    <img src="${book.cover}" alt="${book.title}">
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[9px] font-extrabold text-indigo-600 tracking-widest uppercase bg-indigo-50 px-2 py-1 rounded">${book.category_name}</span>
                        <span class="text-[9px] font-mono text-slate-400 font-bold">${book.code}</span>
                    </div>
                    <h3 class="font-bold text-slate-800 uppercase text-xs leading-snug mb-2 group-hover:text-indigo-600 transition-colors">${book.title}</h3>
                    <p class="text-[11px] text-slate-400 font-medium italic">Oleh: ${book.author}</p>
                </div>
            </div>
        `).join('');
    }

    function showDetail(id) {
        currentActiveId = id;
        const book = books.find(b => b.id === id);
        if(!book) return;

        document.getElementById('modalTitle').innerText = book.title;
        document.getElementById('modalCode').innerText = book.code;
        document.getElementById('modalAuthor').innerText = book.author;
        document.getElementById('modalDesc').innerText = book.desc;
        document.getElementById('modalCover').src = book.cover;
        document.getElementById('modalTag').innerText = book.category_name;

        document.getElementById('bookModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('bookModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // 5. PDF VIEWER (EMBED)
    function openPDF() {
        const book = books.find(b => b.id == currentActiveId);
        if(!book) return;

        document.getElementById('pdfTitle').innerText = `Membaca: ${book.title}`;
        // Menambahkan toolbar=0 agar bersih di touchscreen
        document.getElementById('pdfFrame').src = book.pdf + "#toolbar=0&navpanes=0&scrollbar=0";
        document.getElementById('pdfViewer').classList.remove('hidden');
    }

    function closePDF() {
        document.getElementById('pdfViewer').classList.add('hidden');
        document.getElementById('pdfFrame').src = "";
    }

    // 6. UTILITIES
    function updateActiveState(elementId, isSub = false) {
        const selector = isSub ? '.sub-nav-btn' : '.nav-link';
        document.querySelectorAll(selector).forEach(el => el.classList.remove('active'));
        const activeEl = document.getElementById(elementId);
        if(activeEl) activeEl.classList.add('active');
    }

    function showBreadcrumb(parentName) {
        const bc = document.getElementById('breadcrumb');
        if(bc) {
            bc.classList.remove('hidden');
            document.getElementById('bc-parent').innerText = parentName;
            document.getElementById('bc-separator').classList.add('hidden');
            document.getElementById('bc-child').classList.add('hidden');
        }
    }

    // Jam Real-time
    setInterval(() => {
        const clock = document.getElementById('clock');
        if(clock) clock.innerText = new Date().toLocaleTimeString('id-ID');
    }, 1000);

    // Initial Load saat halaman dibuka
    window.onload = () => displayBooks(books);
</script>
</body>
</html>
