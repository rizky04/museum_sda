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

    <main class="max-w-7xl mx-auto px-6 py-12">

        <div class="mb-12">
            <h2 class="text-4xl font-extrabold text-slate-900 mb-2">Eksplorasi Manuskrip</h2>
            <p class="text-slate-500 max-w-2xl">Menelusuri jejak sejarah melalui digitalisasi naskah kuno koleksi Museum Mpu Tantular Jawa Timur.</p>
        </div>

        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-12">
            <div class="flex flex-wrap gap-3" id="categoryFilters">
                <button onclick="filterCategory('All')" class="cat-btn active">Semua</button>
                @foreach($categories as $cat)
                    <button onclick="filterCategory('{{ $cat->category_name }}')" class="cat-btn">
                        {{ $cat->category_name }}
                    </button>
                @endforeach
            </div>

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
        // Data dari Laravel
        const books = @json($books).map(book => ({
            id: book.id,
            code: book.manuscript_code || "MS-" + book.id,
            category: book.category ? book.category.category_name : 'Koleksi',
            title: book.title,
            author: book.author || 'Anonim',
            desc: book.description || 'Tidak ada deskripsi tersedia.',
            cover: `/storage/${book.cover_image}`,
            pdf: `/storage/${book.pdf_file}`
        }));

        let currentCategory = 'All';
        let currentActiveId = null;

        function displayBooks(data) {
            const grid = document.getElementById('bookGrid');
            grid.innerHTML = data.map(book => `
                <div onclick="showDetail(${book.id})" class="manuskrip-card cursor-pointer group">
                    <div class="overflow-hidden relative">
                        <img src="${book.cover}" alt="${book.title}">
                        <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                             <span class="bg-white/90 text-slate-900 px-4 py-2 rounded-full font-bold text-xs">Lihat Detail</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-[9px] font-extrabold text-indigo-600 tracking-widest uppercase bg-indigo-50 px-2 py-1 rounded">${book.category}</span>
                            <span class="text-[9px] font-mono text-slate-400 font-bold">${book.code}</span>
                        </div>
                        <h3 class="font-bold text-slate-800 uppercase text-sm leading-snug mb-2 group-hover:text-indigo-600 transition-colors">${book.title}</h3>
                        <p class="text-[11px] text-slate-400 font-medium italic">Oleh: ${book.author}</p>
                    </div>
                </div>
            `).join('');
        }

        function filterCategory(cat) {
            currentCategory = cat;
            document.querySelectorAll('.cat-btn').forEach(btn => {
                const btnText = btn.innerText;
                const compareText = cat === 'All' ? 'Semua' : cat;
                btn.classList.toggle('active', btnText === compareText);
            });
            const filtered = cat === 'All' ? books : books.filter(b => b.category === cat);
            displayBooks(filtered);
        }

        function searchBooks() {
            const keyword = document.getElementById('searchInput').value.toLowerCase();
            const filtered = books.filter(b =>
                (currentCategory === 'All' || b.category === currentCategory) &&
                (b.title.toLowerCase().includes(keyword) || b.code.toLowerCase().includes(keyword))
            );
            displayBooks(filtered);
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
            document.getElementById('modalTag').innerText = book.category;

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
            document.getElementById('pdfFrame').src = book.pdf + "#toolbar=0";
            document.getElementById('pdfViewer').classList.remove('hidden');
        }

        function closePDF() {
            document.getElementById('pdfViewer').classList.add('hidden');
            document.getElementById('pdfFrame').src = "";
        }

        // Clock
        setInterval(() => {
            const now = new Date();
            document.getElementById('clock').innerText = now.toLocaleTimeString('id-ID');
        }, 1000);

        // Initial Load
        displayBooks(books);
    </script>
</body>
</html>
