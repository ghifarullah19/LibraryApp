<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Library App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased">

    <nav class="bg-white border-b border-gray-200 px-6 py-4 sticky top-0 z-10">
        <div class="max-w-6xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-8">
                <h1 class="text-xl font-extrabold tracking-tight text-slate-900">
                    Library<span class="text-slate-400 font-medium">App</span>
                </h1>
                <div class="hidden sm:flex gap-6 text-sm font-medium text-slate-500">
                    <a href="/dashboard" class="text-slate-900 border-b-2 border-slate-900 pb-1">Books</a>
                    <a href="/dashboard/authors" class="hover:text-slate-900 transition">Authors</a>
                    <a href="/dashboard/publishers" class="hover:text-slate-900 transition">Publishers</a>
                </div>
            </div>
            <button id="btnLogout" class="text-sm font-medium text-slate-500 hover:text-red-600 transition">Logout</button>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto mt-8 px-4 pb-12">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-6 gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Books Collection</h2>
                <p class="text-sm text-slate-500 mt-1">Kelola direktori buku perpustakaan Anda.</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                <select id="filterAuthor" onchange="fetchBooks(1)" class="w-full md:w-40 bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-900 shadow-sm cursor-pointer">
                    <option value="">Semua Penulis</option>
                </select>

                <select id="filterPublisher" onchange="fetchBooks(1)" class="w-full md:w-40 bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-900 shadow-sm cursor-pointer">
                    <option value="">Semua Penerbit</option>
                </select>

                <div class="relative w-full md:w-56">
                    <span class="absolute left-3 top-2.5 text-gray-400 text-sm">🔍</span>
                    <input type="text" id="searchInput" placeholder="Cari buku..." class="w-full pl-9 pr-4 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-slate-900 transition shadow-sm">
                </div>
                
                <button onclick="openModal()" class="whitespace-nowrap bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition w-full md:w-auto mt-2 md:mt-0">
                    + Tambah Buku
                </button>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider cursor-pointer hover:text-slate-900 transition select-none" onclick="sortData('title')">
                                Judul Buku <span id="icon-title" class="ml-1"></span>
                            </th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Penulis</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Penerbit</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider cursor-pointer hover:text-slate-900 transition select-none" onclick="sortData('publish_year')">
                                Tahun <span id="icon-publish_year" class="ml-1"></span>
                            </th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="divide-y divide-gray-100">
                        <tr><td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">Memuat data...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div id="pagination" class="mt-6 flex justify-end gap-2"></div>
    </div>

    <div id="bookModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm hidden flex items-center justify-center z-50 transition-all">
        <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md border border-gray-100">
            <h2 id="modalTitle" class="text-xl font-bold text-slate-900 mb-6">Tambah Buku</h2>
            <form id="bookForm">
                <input type="hidden" id="bookId">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Judul Buku</label>
                    <input type="text" id="title" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-900">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Penulis</label>
                    <select id="author_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-900 bg-white">
                        <option value="">Memuat penulis...</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Penerbit</label>
                    <select id="publisher_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-900 bg-white">
                        <option value="">Memuat penerbit...</option>
                    </select>
                </div>
                <div class="mb-8">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Tahun Terbit</label>
                    <input type="number" id="publish_year" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-900">
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-100 transition">Batal</button>
                    <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white px-5 py-2 rounded-lg text-sm font-medium shadow-sm transition">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <script type="module">
        const token = localStorage.getItem('jwt_token');
        if (!token) window.location.href = '/';
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

        let currentSortBy = 'created_at';
        let currentSortOrder = 'desc';
        let currentSearch = '';

        // Mengisi dropdown untuk filter sekaligus form modal
        function loadDropdowns() {
            axios.get('/api/authors?limit=100').then(res => {
                let optionsData = '';
                res.data.data.data.forEach(a => optionsData += `<option value="${a.id}">${a.name}</option>`);
                document.getElementById('filterAuthor').innerHTML += optionsData;
                document.getElementById('author_id').innerHTML = '<option value="">-- Pilih Penulis --</option>' + optionsData;
            });
            axios.get('/api/publishers?limit=100').then(res => {
                let optionsData = '';
                res.data.data.data.forEach(p => optionsData += `<option value="${p.id}">${p.name}</option>`);
                document.getElementById('filterPublisher').innerHTML += optionsData;
                document.getElementById('publisher_id').innerHTML = '<option value="">-- Pilih Penerbit --</option>' + optionsData;
            });
        }

        window.sortData = function(column) {
            if (currentSortBy === column) {
                currentSortOrder = currentSortOrder === 'asc' ? 'desc' : 'asc';
            } else {
                currentSortBy = column;
                currentSortOrder = 'asc';
            }
            document.querySelectorAll('th span').forEach(el => el.innerText = '');
            document.getElementById(`icon-${column}`).innerText = currentSortOrder === 'asc' ? '↑' : '↓';
            fetchBooks(1);
        }

        // Modifikasi fetchBooks untuk menyertakan filter_author dan filter_publisher
        window.fetchBooks = function(page = 1, search = currentSearch) {
            currentSearch = search;
            const authorId = document.getElementById('filterAuthor').value;
            const publisherId = document.getElementById('filterPublisher').value;
            
            // Merakit URL dengan semua parameter (Search, Sort, Pagination, Filters)
            const url = `/api/books?page=${page}&search=${search}&sort_by=${currentSortBy}&sort_order=${currentSortOrder}&author_id=${authorId}&publisher_id=${publisherId}`;
            
            axios.get(url).then(response => {
                const books = response.data.data.data;
                const meta = response.data.data;
                const tableBody = document.getElementById('tableBody');
                
                tableBody.innerHTML = '';
                if (books.length === 0) return tableBody.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">Buku tidak ditemukan.</td></tr>';

                books.forEach(book => {
                    const bookData = encodeURIComponent(JSON.stringify(book));
                    tableBody.innerHTML += `
                        <tr class="hover:bg-slate-50 transition duration-150">
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">${book.title}</td>
                            <td class="px-6 py-4 text-sm text-slate-500">${book.author.name}</td>
                            <td class="px-6 py-4 text-sm text-slate-500">${book.publisher.name}</td>
                            <td class="px-6 py-4 text-sm text-slate-500">${book.publish_year}</td>
                            <td class="px-6 py-4 text-sm text-right font-medium">
                                <button onclick="editBook('${bookData}')" class="text-indigo-600 hover:text-indigo-900 mr-4 transition">Edit</button>
                                <button onclick="deleteBook(${book.id})" class="text-red-500 hover:text-red-700 transition">Hapus</button>
                            </td>
                        </tr>
                    `;
                });
                renderPagination(meta, search);
            });
        }

        loadDropdowns();
        fetchBooks();

        window.openModal = function() {
            document.getElementById('bookForm').reset();
            document.getElementById('bookId').value = '';
            document.getElementById('modalTitle').innerText = 'Tambah Buku';
            document.getElementById('bookModal').classList.remove('hidden');
        }

        window.closeModal = function() { document.getElementById('bookModal').classList.add('hidden'); }

        window.editBook = function(encodedData) {
            const book = JSON.parse(decodeURIComponent(encodedData));
            document.getElementById('bookId').value = book.id;
            document.getElementById('title').value = book.title;
            document.getElementById('author_id').value = book.author_id;
            document.getElementById('publisher_id').value = book.publisher_id;
            document.getElementById('publish_year').value = book.publish_year;
            
            document.getElementById('modalTitle').innerText = 'Edit Buku';
            document.getElementById('bookModal').classList.remove('hidden');
        }

        document.getElementById('bookForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const id = document.getElementById('bookId').value;
            const payload = {
                title: document.getElementById('title').value,
                author_id: document.getElementById('author_id').value,
                publisher_id: document.getElementById('publisher_id').value,
                publish_year: document.getElementById('publish_year').value,
            };

            const request = id ? axios.put(`/api/books/${id}`, payload) : axios.post('/api/books', payload);
            request.then(() => {
                closeModal();
                fetchBooks(1); 
            }).catch(() => alert('Gagal menyimpan data. Cek kembali isian Anda.'));
        });

        window.deleteBook = function(id) {
            if(confirm('Yakin ingin menghapus buku ini?')) {
                axios.delete(`/api/books/${id}`).then(() => fetchBooks(1));
            }
        }

        function renderPagination(meta, search) {
            const paginationDiv = document.getElementById('pagination');
            paginationDiv.innerHTML = '';
            if (meta.current_page > 1) {
                paginationDiv.innerHTML += `<button onclick="fetchBooks(${meta.current_page - 1}, '${search}')" class="px-4 py-2 border border-gray-200 text-sm font-medium rounded-lg text-slate-600 hover:bg-slate-50 transition">Sebelumnya</button>`;
            }
            if (meta.current_page < meta.last_page) {
                paginationDiv.innerHTML += `<button onclick="fetchBooks(${meta.current_page + 1}, '${search}')" class="px-4 py-2 border border-gray-200 text-sm font-medium rounded-lg text-slate-600 hover:bg-slate-50 transition">Selanjutnya</button>`;
            }
        }

        document.getElementById('searchInput').addEventListener('keyup', e => fetchBooks(1, e.target.value));
        document.getElementById('btnLogout').addEventListener('click', function() {
            axios.post('/api/logout').finally(() => { localStorage.removeItem('jwt_token'); window.location.href = '/'; });
        });
    </script>
</body>
</html>