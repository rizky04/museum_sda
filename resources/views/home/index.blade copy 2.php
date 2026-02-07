<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Archive | Museum Mpu Tantular</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #fdfaf7; /* Warna dasar krem hangat */
            color: #451a03; /* Coklat gelap tua */
        }

        /* Glassmorphism Header */
        .glass-header {
            background: rgba(255, 252, 249, 0.9);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(214, 211, 209, 0.5);
        }

        /* Nav Link Styling */
        .nav-link {
            padding: 18px 0;
            font-size: 13px;
            font-weight: 700;
            color: #78716c;
            letter-spacing: 0.05em;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
            white-space: nowrap;
        }

        .nav-link.active {
            color: #92400e; /* Coklat Amber */
            border-bottom-color: #92400e;
        }

        /* Modern Card Styling */
        .manuskrip-card {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid #f5ebe0;
        }

        .manuskrip-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(146, 64, 14, 0.1);
        }

        .manuskrip-card img {
            width: 100%;
            height: 260px;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .manuskrip-card:hover img { transform: scale(1.08); }

        /* Sub-Navbar Button */
        .sub-nav-btn {
            padding: 10px 20px;
            background: #ffffff;
            border: 1px solid #e7e5e4;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 700;
            color: #57534e;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .sub-nav-btn.active {
            background: #78350f; /* Coklat Kayu Tua */
            color: white;
            border-color: #78350f;
            box-shadow: 0 4px 12px rgba(120, 53, 15, 0.2);
        }

        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #d6d3d1; border-radius: 10px; }

        /* Modal Gradient Brown Edition */
        .modal-gradient { background: linear-gradient(135deg, #f5f5f4 0%, #e7e5e4 100%); }
    </style>
</head>
<body class="min-h-screen">

    <header class="glass-header sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-800 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-amber-200">
                    <i class="fa-solid fa-scroll text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-extrabold text-stone-900 tracking-tight leading-none">Mpu Tantular</h1>
                    <span class="text-[10px] font-bold text-amber-600 uppercase tracking-[0.2em]">Digital Archive</span>
                </div>
            </div>

            <div class="hidden md:flex relative group w-96">
                <input type="text" id="searchInput" onkeyup="searchBooks()"
                    placeholder="Cari koleksi sejarah..."
                    class="w-full pl-12 pr-4 py-3 bg-stone-100 border-none rounded-2xl outline-none focus:bg-white focus:ring-4 focus:ring-amber-500/10 transition-all">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-3.5 text-stone-400 group-focus-within:text-amber-600"></i>
            </div>

            <div id="clock" class="px-4 py-2 bg-stone-100 rounded-xl font-mono text-xs font-bold text-stone-500">
                00:00:00
            </div>
        </div>
    </header>

    <nav class="sticky top-20 z-40 bg-white border-b border-stone-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex gap-10 overflow-x-auto no-scrollbar items-center" id="parentNav">
                <button onclick="resetToHome()" id="btn-all" class="nav-link active uppercase flex items-center gap-2">
                    <i class="fa-solid fa-house-chimney text-[10px]"></i> SEMUA KOLEKSI
                </button>
                @foreach($categories->where('parent_id', null) as $cat)
                    <button onclick="handleParentClick({{ $cat->id }}, '{{ $cat->category_name }}')" id="parent-{{ $cat->id }}" class="nav-link uppercase">
                        {{ $cat->category_name }}
                    </button>
                @endforeach
            </div>
        </div>

        <div id="subNavbar" class="bg-stone-50 border-t border-stone-100 hidden animate-in slide-in-from-top-2">
            <div class="max-w-7xl mx-auto px-6 flex gap-4 py-4 overflow-x-auto no-scrollbar" id="childNav">
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 py-10">
        <div id="breadcrumb" class="hidden flex items-center gap-2 text-[10px] font-bold text-stone-400 uppercase tracking-widest mb-8">
            <span class="hover:text-amber-700 cursor-pointer" onclick="resetToHome()">KOLEKSI</span>
            <i class="fa-solid fa-chevron-right text-[8px]"></i>
            <span id="bc-parent" class="text-amber-800"></span>
            <span id="bc-separator" class="hidden"><i class="fa-solid fa-chevron-right text-[8px]"></i></span>
            <span id="bc-child" class="hidden text-amber-600"></span>
        </div>

        <div id="bookGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            </div>
    </main>

    <div id="bookModal" class="fixed inset-0 z-[60] hidden flex items-center justify-center p-4 md:p-10 bg-stone-900/95 backdrop-blur-md">
        <div class="bg-white w-full max-w-6xl h-full md:h-[80vh] flex flex-col md:flex-row shadow-2xl overflow-hidden md:rounded-[40px] animate-in zoom-in duration-300">

            <div class="w-full md:w-5/12 modal-gradient p-12 flex items-center justify-center relative border-r border-stone-100">
                <img id="modalCover" src="" class="max-h-full rounded-2xl shadow-2xl transform hover:scale-105 transition-transform duration-500 object-contain bg-white">
            </div>

            <div class="w-full md:w-7/12 flex flex-col bg-white">
                <div class="p-10 pb-0 flex justify-between items-start">
                    <div>
                        <span id="modalTag" class="px-3 py-1 bg-amber-100 text-amber-800 text-[10px] font-extrabold rounded-full uppercase mb-4 inline-block"></span>
                        <h2 id="modalTitle" class="text-3xl font-extrabold text-stone-900 leading-tight"></h2>
                    </div>
                    <button onclick="closeModal()" class="text-stone-300 hover:text-red-500 text-3xl transition-colors">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                </div>

                <div class="flex-grow overflow-y-auto p-10 pt-6 custom-scroll">
                    <div class="grid grid-cols-2 gap-6 mb-8">
                        <div class="bg-stone-50 p-5 rounded-3xl border border-stone-100">
                            <span class="text-[10px] font-extrabold text-stone-400 uppercase tracking-widest block mb-1">Kode Naskah</span>
                            <span id="modalCode" class="text-stone-900 font-bold text-lg"></span>
                        </div>
                        <div class="bg-stone-50 p-5 rounded-3xl border border-stone-100">
                            <span class="text-[10px] font-extrabold text-stone-400 uppercase tracking-widest block mb-1">Penulis / Penyusun</span>
                            <span id="modalAuthor" class="text-stone-900 font-bold text-lg"></span>
                        </div>
                    </div>
                    <div class="mb-6">
                        <span class="text-[10px] font-extrabold text-stone-400 uppercase tracking-widest block mb-2">Deskripsi Lengkap</span>
                        <p id="modalDesc" class="text-stone-600 leading-relaxed text-base italic text-justify"></p>
                    </div>
                </div>

                <div class="p-10 pt-4 border-t border-stone-100">
                    <button onclick="openPDF()" class="w-full py-5 bg-amber-800 text-white font-bold rounded-2xl hover:bg-amber-900 shadow-xl shadow-amber-100 transition-all flex items-center justify-center gap-3 active:scale-[0.98]">
                        <i class="fa-solid fa-book-open"></i>
                        BACA DIGITALISASI MANUSKRIP
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="pdfViewer" class="fixed inset-0 z-[70] hidden bg-stone-950 flex flex-col">
        <div class="bg-stone-900 p-4 flex justify-between items-center text-white">
            <div class="flex items-center gap-4">
                <i class="fa-solid fa-file-pdf text-amber-500 text-2xl"></i>
                <h3 id="pdfTitle" class="font-bold text-sm uppercase tracking-widest"></h3>
            </div>
            <button onclick="closePDF()" class="bg-red-600 px-6 py-2 rounded-xl font-bold hover:bg-red-700 transition-all">TUTUP PANEL</button>
        </div>
        <iframe id="pdfFrame" src="" class="flex-grow w-full border-none bg-stone-800"></iframe>
    </div>

    <script>
        // JS logic tetap sama, hanya sesuaikan class CSS jika ada perubahan manual di template string
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

        function resetToHome() {
            updateActiveState('btn-all');
            document.getElementById('subNavbar').classList.add('hidden');
            document.getElementById('breadcrumb').classList.add('hidden');
            displayBooks(books);
        }

        function handleParentClick(id, name) {
            updateActiveState(`parent-${id}`);
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
            } else { subNav.classList.add('hidden'); }

            showBreadcrumb(name);
            filterByCategoryId(id);
        }

        function handleChildClick(id, name) {
            updateActiveState(`child-${id}`, true);
            document.getElementById('bc-separator').classList.remove('hidden');
            document.getElementById('bc-child').classList.remove('hidden');
            document.getElementById('bc-child').innerText = name;
            filterByCategoryId(id);
        }

        function filterByCategoryId(id) {
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

        function displayBooks(data) {
            const grid = document.getElementById('bookGrid');
            if (data.length === 0) {
                grid.innerHTML = `<div class="col-span-full py-32 text-center text-stone-400 font-bold uppercase tracking-widest">Tidak ada koleksi ditemukan</div>`;
                return;
            }
            grid.innerHTML = data.map(book => `
                <div onclick="showDetail(${book.id})" class="manuskrip-card cursor-pointer group">
                    <div class="relative overflow-hidden">
                        <img src="${book.cover}" alt="${book.title}">
                        <div class="absolute inset-0 bg-amber-900/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-[9px] font-extrabold text-amber-800 tracking-widest uppercase bg-amber-50 px-2 py-1 rounded-lg">${book.category_name}</span>
                            <span class="text-[9px] font-mono text-stone-400 font-bold">${book.code}</span>
                        </div>
                        <h3 class="font-bold text-stone-800 text-sm leading-snug group-hover:text-amber-700 transition-colors uppercase">${book.title}</h3>
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

        function openPDF() {
            const book = books.find(b => b.id == currentActiveId);
            if(!book) return;
            document.getElementById('pdfTitle').innerText = `Membaca: ${book.title}`;
            document.getElementById('pdfFrame').src = book.pdf + "#toolbar=0&navpanes=0";
            document.getElementById('pdfViewer').classList.remove('hidden');
        }

        function closePDF() {
            document.getElementById('pdfViewer').classList.add('hidden');
            document.getElementById('pdfFrame').src = "";
        }

        function updateActiveState(elementId, isSub = false) {
            document.querySelectorAll(isSub ? '.sub-nav-btn' : '.nav-link').forEach(el => el.classList.remove('active'));
            const activeEl = document.getElementById(elementId);
            if(activeEl) activeEl.classList.add('active');
        }

        function showBreadcrumb(name) {
            document.getElementById('breadcrumb').classList.remove('hidden');
            document.getElementById('bc-parent').innerText = name;
            document.getElementById('bc-separator').classList.add('hidden');
            document.getElementById('bc-child').classList.add('hidden');
        }

        setInterval(() => {
            document.getElementById('clock').innerText = new Date().toLocaleTimeString('id-ID');
        }, 1000);

        window.onload = () => displayBooks(books);
    </script>
</body>
</html>
