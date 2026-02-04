<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arsip Museum Mpu Tantular</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');

        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }

        /* Kartu Katalog ala Manuskripedia */
        .manuskrip-card {
            background: white;
            border-radius: 4px;
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
        }
        .manuskrip-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 20px -5px rgba(0, 0, 0, 0.1);
        }
        .manuskrip-card img {
            width: 100%;
            height: 320px;
            object-fit: cover;
        }

        /* Navigasi Kategori */
        .cat-btn {
            padding: 8px 20px;
            border-radius: 99px;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.2s;
            border: 1px solid #e2e8f0;
            background: white;
            color: #64748b;
        }
        .cat-btn.active {
            background: #1e293b;
            color: white;
            border-color: #1e293b;
        }

        .custom-scroll::-webkit-scrollbar { width: 5px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

        .info-row {
            display: grid;
            grid-template-columns: 160px 1fr;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }
    </style>
</head>
<body class="min-h-screen">

    <header class="bg-white border-b border-gray-200 py-6 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tighter">Arsip Museum Mpu Tantular <span class="text-blue-600">DIGITAL</span></h1>
            </div>
            <div id="clock" class="text-lg font-mono text-slate-400">00:00</div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-10">

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
            {{-- <div class="flex flex-wrap gap-2" id="categoryFilters">
                <button onclick="filterCategory('All')" class="cat-btn active">Semua</button>
                <button onclick="filterCategory('Babad')" class="cat-btn">Babad</button>
                <button onclick="filterCategory('Hikayat')" class="cat-btn">Hikayat</button>
                <button onclick="filterCategory('Serat')" class="cat-btn">Serat</button>
                <button onclick="filterCategory('Primbon')" class="cat-btn">Primbon</button>
            </div> --}}

            <div class="flex flex-wrap gap-2" id="categoryFilters">
                <button onclick="filterCategory('All')" class="cat-btn active">Semua</button>
                @foreach($categories as $cat)
                    <button onclick="filterCategory('{{ $cat->category_name }}')" class="cat-btn">
                        {{ $cat->category_name }}
                    </button>
                @endforeach
            </div>

            <div class="relative w-full md:w-96">
                <input type="text" id="searchInput" onkeyup="searchBooks()"
                    placeholder="Cari judul atau kode..."
                    class="w-full pl-12 pr-4 py-3 bg-white border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-4 text-slate-400"></i>
            </div>
        </div>

        <div id="bookGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            </div>
    </main>

    <div id="bookModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm">
        <div class="bg-white w-full max-w-6xl h-[90vh] flex flex-col md:flex-row shadow-2xl overflow-hidden rounded-lg">

            <div class="w-full md:w-5/12 bg-slate-100 p-8 flex items-center justify-center relative">
                <button onclick="closeModal()" class="absolute top-4 left-4 md:hidden text-2xl text-slate-800">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
                <img id="modalCover" src="" class="max-h-full shadow-2xl border-8 border-white object-contain">
            </div>

            <div class="w-full md:w-7/12 p-10 overflow-y-auto bg-white custom-scroll">
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <span id="modalTag" class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded uppercase mb-2 inline-block"></span>
                        <h2 id="modalTitle" class="text-4xl font-bold text-slate-900 leading-tight uppercase"></h2>
                    </div>
                    <button onclick="closeModal()" class="hidden md:block text-slate-300 hover:text-red-500 text-3xl transition-colors">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                </div>

                <div class="space-y-1 mb-10">
                    <div class="info-row">
                        <span class="font-bold text-slate-500 text-sm uppercase">Kode Naskah</span>
                        <span id="modalCode" class="text-slate-900 font-mono font-bold"></span>
                    </div>
                    <div class="info-row">
                        <span class="font-bold text-slate-500 text-sm uppercase">Penulis/Penyalin</span>
                        <span id="modalAuthor" class="text-slate-900"></span>
                    </div>
                    <div class="info-row">
                        <span class="font-bold text-slate-500 text-sm uppercase">Asal Wilayah</span>
                        <span id="modalOrigin" class="text-slate-900">Jawa Tengah</span>
                    </div>
                    <div class="info-row">
                        <span class="font-bold text-slate-500 text-sm uppercase">Kondisi Fisik</span>
                        <span class="text-slate-900">Baik (Digital High Res)</span>
                    </div>
                    <div class="info-row">
                        <span class="font-bold text-slate-500 text-sm uppercase">Ringkasan</span>
                        <p id="modalDesc" class="text-slate-600 leading-relaxed text-sm text-justify"></p>
                    </div>
                </div>

                <button onclick="window.open('example.pdf')" class="w-full py-4 bg-slate-900 text-white font-bold rounded hover:bg-blue-600 transition-all flex items-center justify-center gap-3">
                    <i class="fa-solid fa-book-open-reader"></i>
                    BACA DIGITALISASI LENGKAP
                </button>
            </div>
        </div>
    </div>
    <div id="pdfViewer" class="fixed inset-0 z-[60] hidden bg-slate-900 flex flex-col">
        <div class="bg-slate-800 p-4 flex justify-between items-center text-white border-b border-slate-700">
            <h3 id="pdfTitle" class="font-bold uppercase tracking-wider">Membaca Manuskrip</h3>
            <button onclick="closePDF()" class="bg-red-600 hover:bg-red-700 px-6 py-2 rounded-lg font-bold transition-all">
                KEMBALI KE DETAIL
            </button>
        </div>

        <div class="flex-grow w-full h-full">
            <iframe id="pdfFrame" src="" class="w-full h-full border-none"></iframe>
        </div>
    </div>

    <script>
      const books = @json($books).map(book => ({
        id: book.id,
        code: "MS-" + book.id, // Sesuaikan jika ada kolom kode naskah sendiri
        category: book.category ? book.category.category_name : 'Uncategorized',
        title: book.title,
        author: book.author,
        desc: book.description,
        // Path storage Laravel
        cover: `/storage/${book.cover_image}`,
        pdf: `/storage/${book.pdf_file}`
    }));

        let currentCategory = 'All';

        function displayBooks(data) {
            const grid = document.getElementById('bookGrid');
            grid.innerHTML = data.map(book => `
                <div onclick="showDetail(${book.id})" class="manuskrip-card cursor-pointer">
                    <img src="${book.cover}" alt="${book.title}">
                    <div class="p-5">
                        <div class="text-[10px] font-black text-blue-600 mb-1 tracking-widest uppercase">${book.category} | ${book.code}</div>
                        <h3 class="font-bold text-slate-800 uppercase text-sm leading-tight mb-2">${book.title}</h3>
                        <p class="text-xs text-slate-400">Oleh: ${book.author}</p>
                    </div>
                </div>
            `).join('');
        }

        function filterCategory(cat) {
            currentCategory = cat;

            // Update button UI
            document.querySelectorAll('.cat-btn').forEach(btn => {
                btn.classList.toggle('active', btn.innerText === (cat === 'All' ? 'Semua' : cat));
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
        const book = books.find(b => b.id === id);
        if(!book) return;

        document.getElementById('modalTitle').innerText = book.title;
        document.getElementById('modalCode').innerText = book.code;
        document.getElementById('modalAuthor').innerText = book.author;
        document.getElementById('modalDesc').innerText = book.desc;
        document.getElementById('modalCover').src = book.cover;
        document.getElementById('modalTag').innerText = book.category;

        // Update link tombol BACA PDF
        const pdfBtn = document.querySelector('#bookModal button[onclick^="window.open"]');
        pdfBtn.setAttribute('onclick', `window.open('${book.pdf}', '_blank')`);

        document.getElementById('bookModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

        // function showDetail(id) {
        //     const book = books.find(b => b.id === id);
        //     if(!book) return;

        //     document.getElementById('modalTitle').innerText = book.title;
        //     document.getElementById('modalCode').innerText = book.code;
        //     document.getElementById('modalAuthor').innerText = book.author;
        //     document.getElementById('modalDesc').innerText = book.desc;
        //     document.getElementById('modalCover').src = book.cover;
        //     document.getElementById('modalTag').innerText = book.category;

        //     document.getElementById('bookModal').classList.remove('hidden');
        //     document.body.style.overflow = 'hidden';
        // }

        function closeModal() {
            document.getElementById('bookModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Initial
        displayBooks(books);
        setInterval(() => {
            const now = new Date();
            document.getElementById('clock').innerText = now.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
        }, 1000);


        function openPDF() {
        const book = books.find(b => b.id == currentActiveId); // Ambil ID buku yang sedang dibuka
        if(!book) return;

        const pdfUrl = book.pdf;
        const viewerTitle = book.title;

        // Set Judul di Viewer
        document.getElementById('pdfTitle').innerText = `Membaca: ${viewerTitle}`;

        // Load PDF ke iframe
        // Kita gunakan browser native viewer agar lebih ringan untuk kiosk
        document.getElementById('pdfFrame').src = pdfUrl + "#toolbar=0&navpanes=0&scrollbar=0";

        // Tampilkan Viewer
        document.getElementById('pdfViewer').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closePDF() {
        document.getElementById('pdfViewer').classList.add('hidden');
        document.getElementById('pdfFrame').src = ""; // Stop loading PDF saat ditutup
    }

    // Ubah sedikit fungsi showDetail agar kita tahu ID mana yang aktif
    let currentActiveId = null;
    function showDetail(id) {
        currentActiveId = id; // Simpan ID yang aktif
        const book = books.find(b => b.id === id);
        if(!book) return;

        document.getElementById('modalTitle').innerText = book.title;
        document.getElementById('modalCode').innerText = book.code;
        document.getElementById('modalAuthor').innerText = book.author;
        document.getElementById('modalDesc').innerText = book.desc;
        document.getElementById('modalCover').src = book.cover;
        document.getElementById('modalTag').innerText = book.category;

        // Update tombol baca agar memanggil fungsi openPDF internal
        const pdfBtn = document.querySelector('#bookModal button[onclick^="window.open"]');
        pdfBtn.setAttribute('onclick', `openPDF()`);

        document.getElementById('bookModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    </script>
</body>
</html>