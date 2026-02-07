<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Digital Archive | Museum Mpu Tantular</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fdfaf7; color: #451a03; }
        .glass-header { background: rgba(255, 252, 249, 0.9); backdrop-filter: blur(12px); border-bottom: 1px solid rgba(214, 211, 209, 0.5); }
        .nav-link { padding: 18px 0; font-size: 13px; font-weight: 700; color: #78716c; letter-spacing: 0.05em; border-bottom: 3px solid transparent; transition: all 0.3s; }
        .nav-link.active { color: #92400e; border-bottom-color: #92400e; }
        .manuskrip-card { background: white; border-radius: 24px; overflow: hidden; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); border: 1px solid #f5ebe0; }
        .manuskrip-card:hover { transform: translateY(-8px); box-shadow: 0 20px 25px -5px rgba(146, 64, 14, 0.1); }
        .sub-nav-btn { padding: 10px 20px; background: #ffffff; border: 1px solid #e7e5e4; border-radius: 12px; font-size: 12px; font-weight: 700; color: #57534e; transition: all 0.2s; }
        .sub-nav-btn.active { background: #78350f; color: white; border-color: #78350f; box-shadow: 0 4px 12px rgba(120, 53, 15, 0.2); }
        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #d6d3d1; border-radius: 10px; }
        .modal-gradient { background: linear-gradient(135deg, #f5f5f4 0%, #e7e5e4 100%); }
        .no-scrollbar::-webkit-scrollbar { display: none; }
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
                <input type="text" id="searchInput" onkeyup="searchBooks()" placeholder="Cari koleksi sejarah..." class="w-full pl-12 pr-4 py-3 bg-stone-100 border-none rounded-2xl outline-none focus:bg-white focus:ring-4 focus:ring-amber-500/10 transition-all">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-3.5 text-stone-400 group-focus-within:text-amber-600"></i>
            </div>
            <div id="clock" class="px-4 py-2 bg-stone-100 rounded-xl font-mono text-xs font-bold text-stone-500">00:00:00</div>
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
        <div id="subNavbar" class="bg-stone-50 border-t border-stone-100 hidden">
            <div class="max-w-7xl mx-auto px-6 flex gap-4 py-4 overflow-x-auto no-scrollbar" id="childNav"></div>
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
        <div id="bookGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8"></div>
    </main>

    <div id="bookModal" class="fixed inset-0 z-[60] hidden flex items-center justify-center p-0 md:p-10 bg-stone-900/95 backdrop-blur-md">
        <div class="bg-white w-full max-w-6xl h-full md:h-[85vh] flex flex-col md:flex-row shadow-2xl overflow-y-auto md:overflow-hidden md:rounded-[40px] animate-in zoom-in duration-300">

            <div class="md:hidden flex justify-end p-4 sticky top-0 bg-white z-10 border-b border-stone-100">
                <button onclick="closeModal()" class="text-stone-400 text-2xl"><i class="fa-solid fa-circle-xmark"></i></button>
            </div>

            <div class="w-full md:w-5/12 modal-gradient p-6 md:p-12 flex items-center justify-center relative border-b md:border-b-0 md:border-r border-stone-100 min-h-[300px] md:min-h-0">
                <img id="modalCover" src="" class="h-64 md:h-auto md:max-h-full rounded-2xl shadow-2xl object-contain bg-white transition-transform duration-500 hover:scale-105">
            </div>

            <div class="w-full md:w-7/12 flex flex-col bg-white overflow-visible md:overflow-hidden">
                <div class="p-6 md:p-10 pb-2 md:pb-4 flex justify-between items-start">
                    <div>
                        <span id="modalTag" class="px-3 py-1 bg-amber-100 text-amber-800 text-[10px] font-extrabold rounded-full uppercase mb-3 inline-block"></span>
                        <h2 id="modalTitle" class="text-2xl md:text-3xl font-extrabold text-stone-900 leading-tight"></h2>
                    </div>
                    <button onclick="closeModal()" class="hidden md:block text-stone-300 hover:text-red-500 text-3xl transition-colors">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                </div>

                <div class="flex-grow overflow-y-auto p-6 md:p-10 pt-0 custom-scroll">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3 md:gap-4 mb-6 md:mb-8">
                        <div class="bg-stone-50 p-3 md:p-4 rounded-2xl border border-stone-100">
                            <span class="text-[8px] md:text-[9px] font-extrabold text-stone-400 uppercase block mb-1">Kode Naskah</span>
                            <span id="modalCode" class="text-stone-900 font-bold text-xs md:text-sm"></span>
                        </div>
                        <div class="bg-stone-50 p-3 md:p-4 rounded-2xl border border-stone-100">
                            <span class="text-[8px] md:text-[9px] font-extrabold text-stone-400 uppercase block mb-1">Penulis</span>
                            <span id="modalAuthor" class="text-stone-900 font-bold text-xs md:text-sm"></span>
                        </div>
                        <div class="bg-stone-50 p-3 md:p-4 rounded-2xl border border-stone-100 col-span-2 md:col-span-1">
                            <span class="text-[8px] md:text-[9px] font-extrabold text-stone-400 uppercase block mb-1">Dilihat</span>
                            <span id="modalViews" class="text-amber-800 font-bold text-xs md:text-sm">0 kali</span>
                        </div>
                    </div>

                    <div class="mb-8">
                        <span class="text-[10px] font-extrabold text-stone-400 uppercase tracking-widest block mb-2">Deskripsi</span>
                        <p id="modalDesc" class="text-stone-600 leading-relaxed text-sm italic text-justify"></p>
                    </div>

                    <div class="border-t border-stone-100 pt-6">
                        <h4 class="text-xs font-extrabold text-amber-900 uppercase tracking-widest mb-4"><i class="fa-solid fa-comments mr-2"></i>Diskusi</h4>
                        <div class="bg-stone-50 p-4 md:p-5 rounded-2xl mb-6 border border-stone-200">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                                <input type="text" id="commentName" placeholder="Nama Anda" class="w-full bg-white border border-stone-200 rounded-xl px-4 py-2 text-sm outline-none focus:border-amber-500">
                                <input type="text" id="commentPhone" placeholder="WhatsApp" class="w-full bg-white border border-stone-200 rounded-xl px-4 py-2 text-sm outline-none focus:border-amber-500">
                            </div>
                            <textarea id="commentText" rows="2" placeholder="Tulis komentar..." class="w-full bg-white border border-stone-200 rounded-xl px-4 py-2 text-sm outline-none focus:border-amber-500 mb-3"></textarea>
                            <button onclick="submitComment()" class="w-full py-3 bg-amber-800 text-white text-xs font-bold rounded-xl hover:bg-amber-900 transition-all">KIRIM</button>
                        </div>
                        <div id="commentsList" class="space-y-4 pb-10"></div>
                    </div>
                </div>

                <div class="p-6 md:p-8 border-t border-stone-100 bg-white sticky bottom-0 md:relative">
                    <button onclick="openPDF()" class="w-full py-4 bg-amber-700 text-white font-bold rounded-2xl hover:bg-amber-800 flex items-center justify-center gap-3 shadow-lg">
                        <i class="fa-solid fa-book-open"></i> BACA DIGITALISASI
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="pdfViewer" class="fixed inset-0 z-[70] hidden bg-stone-950 flex flex-col">
        <div class="bg-stone-900 p-4 flex justify-between items-center text-white">
            <h3 id="pdfTitle" class="font-bold text-sm uppercase"></h3>
            <button onclick="closePDF()" class="bg-red-600 px-6 py-2 rounded-xl font-bold">TUTUP</button>
        </div>
        <iframe id="pdfFrame" src="" class="flex-grow w-full border-none"></iframe>
    </div>

    <script>
        const allCategories = @json($categories);
        const books = @json($books).map(book => ({
            id: book.id,
            category_id: book.category_id,
            category_name: book.category ? book.category.category_name : 'Koleksi',
            title: book.title,
            code: book.manuscript_code || "MS-" + book.id,
            author: book.author || 'Anonim',
            desc: book.description || 'Tidak ada deskripsi.',
            cover: `/storage/${book.cover_image}`,
            pdf: `/storage/${book.pdf_file}`,
            view_count: book.view_count || 0
        }));

        let currentActiveId = null;

        function displayBooks(data) {
            const grid = document.getElementById('bookGrid');
            grid.innerHTML = data.length === 0 ? `<div class="col-span-full py-32 text-center text-stone-400 font-bold uppercase">Tidak ditemukan</div>` :
            data.map(book => `
                <div onclick="showDetail(${book.id})" class="manuskrip-card cursor-pointer group">
                    <div class="relative overflow-hidden h-64">
                        <img src="${book.cover}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    </div>
                    <div class="p-6">
                        <span class="text-[9px] font-extrabold text-amber-800 uppercase bg-amber-50 px-2 py-1 rounded-lg">${book.category_name}</span>
                        <h3 class="font-bold text-stone-800 text-sm mt-3 uppercase group-hover:text-amber-700">${book.title}</h3>
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
            document.getElementById('modalViews').innerText = book.view_count + " kali";
            document.getElementById('modalDesc').innerText = book.desc;
            document.getElementById('modalCover').src = book.cover;
            document.getElementById('modalTag').innerText = book.category_name;

            document.getElementById('bookModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            fetchComments(id);
        }

        async function openPDF() {
            const book = books.find(b => b.id == currentActiveId);
            if(book) {
                // Increment View Counter ke Backend
                try {
                    fetch(`/books/${currentActiveId}/view`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                    });
                    book.view_count++; // Update tampilan lokal
                    document.getElementById('modalViews').innerText = book.view_count + " kali";
                } catch (e) {}

                document.getElementById('pdfTitle').innerText = book.title;
                document.getElementById('pdfFrame').src = book.pdf + "#toolbar=0";
                document.getElementById('pdfViewer').classList.remove('hidden');
            }
        }

        async function fetchComments(bookId) {
            const list = document.getElementById('commentsList');
            list.innerHTML = '<p class="text-stone-400 text-xs italic">Memuat...</p>';
            try {
                const response = await fetch(`/comments/${bookId}`);
                const comments = await response.json();
                list.innerHTML = comments.length === 0 ? '<p class="text-stone-400 text-xs italic">Belum ada diskusi.</p>' :
                comments.map(c => `
                    <div class="bg-stone-50 p-4 rounded-xl border border-stone-100">
                        <div class="flex justify-between items-center mb-1">
                            <span class="font-bold text-amber-900 text-xs">${c.name}</span>
                            <span class="text-[9px] text-stone-400">${new Date(c.created_at).toLocaleDateString('id-ID')}</span>
                        </div>
                        <p class="text-stone-600 text-sm">${c.comment}</p>
                    </div>
                `).join('');
            } catch (e) { list.innerHTML = '<p class="text-red-400 text-xs">Gagal memuat.</p>'; }
        }

        async function submitComment() {
            const name = document.getElementById('commentName').value;
            const phone = document.getElementById('commentPhone').value;
            const comment = document.getElementById('commentText').value;
            if (!name || !comment) return alert('Isi nama dan komentar!');

            try {
                const response = await fetch('/comments', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ book_id: currentActiveId, name, phone, comment })
                });
                if (response.ok) {
                    document.getElementById('commentText').value = '';
                    fetchComments(currentActiveId);
                }
            } catch (e) { alert('Gagal mengirim.'); }
        }

        function closeModal() { document.getElementById('bookModal').classList.add('hidden'); document.body.style.overflow = 'auto'; }
        function closePDF() { document.getElementById('pdfViewer').classList.add('hidden'); document.getElementById('pdfFrame').src = ""; }
        function searchBooks() {
            const kw = document.getElementById('searchInput').value.toLowerCase();
            displayBooks(books.filter(b => b.title.toLowerCase().includes(kw) || b.code.toLowerCase().includes(kw)));
        }
        function resetToHome() {
            document.querySelectorAll('.nav-link').forEach(el => el.classList.remove('active'));
            document.getElementById('btn-all').classList.add('active');
            document.getElementById('subNavbar').classList.add('hidden');
            document.getElementById('breadcrumb').classList.add('hidden');
            displayBooks(books);
        }
        function handleParentClick(id, name) {
            document.querySelectorAll('.nav-link').forEach(el => el.classList.remove('active'));
            document.getElementById(`parent-${id}`).classList.add('active');
            const children = allCategories.filter(c => c.parent_id === id);
            const subNav = document.getElementById('subNavbar');
            if (children.length > 0) {
                subNav.classList.remove('hidden');
                document.getElementById('childNav').innerHTML = children.map(c => `<button onclick="handleChildClick(${c.id}, '${c.category_name}')" class="sub-nav-btn">${c.category_name.toUpperCase()}</button>`).join('');
            } else { subNav.classList.add('hidden'); }
            document.getElementById('breadcrumb').classList.remove('hidden');
            document.getElementById('bc-parent').innerText = name;
            filterByCategoryId(id);
        }
        function handleChildClick(id, name) {
            document.getElementById('bc-child').innerText = name;
            filterByCategoryId(id);
        }
        function filterByCategoryId(id) {
            const getIds = (pId) => {
                let res = [pId];
                allCategories.filter(c => c.parent_id === pId).forEach(c => res = [...res, ...getIds(c.id)]);
                return res;
            };
            displayBooks(books.filter(b => getIds(id).includes(b.category_id)));
        }
        setInterval(() => { document.getElementById('clock').innerText = new Date().toLocaleTimeString('id-ID'); }, 1000);
        window.onload = () => displayBooks(books);
    </script>
</body>
</html>
