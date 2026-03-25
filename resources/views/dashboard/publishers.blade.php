<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publishers - Library App</title>
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
                    <a href="/dashboard" class="hover:text-slate-900 transition">Books</a>
                    <a href="/dashboard/authors" class="hover:text-slate-900 transition">Authors</a>
                    <a href="/dashboard/publishers" class="text-slate-900 border-b-2 border-slate-900 pb-1">Publishers</a>
                </div>
            </div>
            <button id="btnLogout" class="text-sm font-medium text-slate-500 hover:text-red-600 transition">Logout</button>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto mt-8 px-4 pb-12">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-6 gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Publishers Directory</h2>
                <p class="text-sm text-slate-500 mt-1">Kelola data mitra penerbit perpustakaan Anda.</p>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <div class="relative w-full sm:w-64">
                    <span class="absolute left-3 top-2.5 text-gray-400 text-sm">🔍</span>
                    <input type="text" id="searchInput" placeholder="Cari penerbit..." class="w-full pl-9 pr-4 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition shadow-sm">
                </div>
                <button onclick="openModal()" class="whitespace-nowrap bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition">
                    + Tambah Penerbit
                </button>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider cursor-pointer hover:text-slate-900 transition select-none w-1/3" onclick="sortData('name')">
                                Nama Penerbit <span id="icon-name" class="ml-1"></span>
                            </th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Alamat Lengkap</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right w-48">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="divide-y divide-gray-100">
                        <tr><td colspan="3" class="px-6 py-8 text-center text-sm text-gray-500">Memuat data...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="pagination" class="mt-6 flex justify-end gap-2"></div>
    </div>

    <div id="publisherModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm hidden flex items-center justify-center z-50 transition-all">
        <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md border border-gray-100">
            <h2 id="modalTitle" class="text-xl font-bold text-slate-900 mb-6">Tambah Penerbit</h2>
            <form id="publisherForm">
                <input type="hidden" id="publisherId">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Penerbit</label>
                    <input type="text" id="name" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                </div>
                <div class="mb-8">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Alamat</label>
                    <textarea id="address" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent"></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-100 transition">Batal</button>
                    <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white px-5 py-2 rounded-lg text-sm font-medium transition shadow-sm">Simpan Data</button>
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

        window.sortData = function(column) {
            if (currentSortBy === column) {
                currentSortOrder = currentSortOrder === 'asc' ? 'desc' : 'asc';
            } else {
                currentSortBy = column;
                currentSortOrder = 'asc';
            }
            document.querySelectorAll('th span').forEach(el => el.innerText = '');
            document.getElementById(`icon-${column}`).innerText = currentSortOrder === 'asc' ? '↑' : '↓';
            fetchPublishers(1);
        }

        window.fetchPublishers = function(page = 1, search = currentSearch) {
            currentSearch = search;
            const url = `/api/publishers?page=${page}&search=${search}&sort_by=${currentSortBy}&sort_order=${currentSortOrder}`;

            axios.get(url).then(response => {
                const publishers = response.data.data.data;
                const meta = response.data.data;
                const tableBody = document.getElementById('tableBody');
                
                tableBody.innerHTML = '';
                if (publishers.length === 0) return tableBody.innerHTML = '<tr><td colspan="3" class="px-6 py-8 text-center text-sm text-gray-500">Penerbit tidak ditemukan.</td></tr>';

                publishers.forEach(publisher => {
                    const publisherData = encodeURIComponent(JSON.stringify(publisher));
                    tableBody.innerHTML += `
                        <tr class="hover:bg-slate-50 transition duration-150">
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">${publisher.name}</td>
                            <td class="px-6 py-4 text-sm text-slate-500 truncate max-w-xs">${publisher.address || '-'}</td>
                            <td class="px-6 py-4 text-sm text-right font-medium">
                                <button onclick="editPublisher('${publisherData}')" class="text-indigo-600 hover:text-indigo-900 mr-4 transition">Edit</button>
                                <button onclick="deletePublisher(${publisher.id})" class="text-red-500 hover:text-red-700 transition">Hapus</button>
                            </td>
                        </tr>
                    `;
                });
                renderPagination(meta, search);
            });
        }

        fetchPublishers();

        window.openModal = function() {
            document.getElementById('publisherForm').reset();
            document.getElementById('publisherId').value = '';
            document.getElementById('modalTitle').innerText = 'Tambah Penerbit';
            document.getElementById('publisherModal').classList.remove('hidden');
        }

        window.closeModal = function() { document.getElementById('publisherModal').classList.add('hidden'); }

        window.editPublisher = function(encodedData) {
            const publisher = JSON.parse(decodeURIComponent(encodedData));
            document.getElementById('publisherId').value = publisher.id;
            document.getElementById('name').value = publisher.name;
            document.getElementById('address').value = publisher.address || '';
            document.getElementById('modalTitle').innerText = 'Edit Penerbit';
            document.getElementById('publisherModal').classList.remove('hidden');
        }

        document.getElementById('publisherForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const id = document.getElementById('publisherId').value;
            const payload = { name: document.getElementById('name').value, address: document.getElementById('address').value };
            const request = id ? axios.put(`/api/publishers/${id}`, payload) : axios.post('/api/publishers', payload);

            request.then(() => { closeModal(); fetchPublishers(1); }).catch(() => alert('Gagal menyimpan data.'));
        });

        window.deletePublisher = function(id) {
            if(confirm('Yakin ingin menghapus penerbit ini? (Semua buku miliknya akan terhapus!)')) {
                axios.delete(`/api/publishers/${id}`).then(() => fetchPublishers(1));
            }
        }

        function renderPagination(meta, search) {
            const paginationDiv = document.getElementById('pagination');
            paginationDiv.innerHTML = '';
            if (meta.current_page > 1) paginationDiv.innerHTML += `<button onclick="fetchPublishers(${meta.current_page - 1}, '${search}')" class="px-4 py-2 border border-gray-200 text-sm font-medium rounded-lg text-slate-600 hover:bg-slate-50 transition">Sebelumnya</button>`;
            if (meta.current_page < meta.last_page) paginationDiv.innerHTML += `<button onclick="fetchPublishers(${meta.current_page + 1}, '${search}')" class="px-4 py-2 border border-gray-200 text-sm font-medium rounded-lg text-slate-600 hover:bg-slate-50 transition">Selanjutnya</button>`;
        }

        document.getElementById('searchInput').addEventListener('keyup', e => fetchPublishers(1, e.target.value));
        document.getElementById('btnLogout').addEventListener('click', function() {
            axios.post('/api/logout').finally(() => { localStorage.removeItem('jwt_token'); window.location.href = '/'; });
        });
    </script>
</body>
</html>