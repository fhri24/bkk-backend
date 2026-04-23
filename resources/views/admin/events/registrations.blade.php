@extends('layouts.admin')

@section('title', 'Daftar Peserta Acara')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 flex items-center">
                        <i class="fas fa-calendar-check text-blue-600 mr-3"></i>
                        Daftar Peserta Acara
                    </h1>
                    <p class="text-slate-600 mt-2">Kelola pendaftaran dan kehadiran peserta acara</p>
                </div>
            </div>
        </div>

        <!-- Filter Event -->
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 mb-6">
            <div class="flex items-center gap-4 flex-wrap">
                <div class="flex-1 min-w-xs">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Pilih Acara</label>
                    <select id="eventFilter" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">-- Semua Acara --</option>
                        <option value="jobfair2026">Job Fair 2026</option>
                        <option value="workshop-interview">Workshop Interview Skill</option>
                        <option value="training-cv-portfolio">Training CV & Portfolio</option>
                        <option value="jobhugging">Job Hugging: Keluar dari Zona Nyaman</option>
                        <option value="interview">Interview Preparation Masterclass</option>
                        <option value="planning">Career Planning Seminar</option>
                    </select>
                </div>
                <div class="flex-1 min-w-xs">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Filter Status</label>
                    <select id="statusFilter" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">-- Semua Status --</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="attended">Attended</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button onclick="loadRegistrations()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                    <button onclick="exportToCSV()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition">
                        <i class="fas fa-download mr-2"></i>Export
                    </button>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                <div class="text-slate-600 text-sm font-medium">Total Registrasi</div>
                <div class="text-3xl font-bold text-blue-600 mt-2" id="totalCount">0</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                <div class="text-slate-600 text-sm font-medium">Pending</div>
                <div class="text-3xl font-bold text-yellow-600 mt-2" id="pendingCount">0</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                <div class="text-slate-600 text-sm font-medium">Confirmed</div>
                <div class="text-3xl font-bold text-blue-600 mt-2" id="confirmedCount">0</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                <div class="text-slate-600 text-sm font-medium">Attended</div>
                <div class="text-3xl font-bold text-green-600 mt-2" id="attendedCount">0</div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Nama</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Email</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Telepon</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Institusi</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Tgl Daftar</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-slate-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="registrationsTableBody" class="divide-y divide-slate-200">
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-slate-500">Pilih acara terlebih dahulu</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 text-white flex justify-between items-center">
            <h2 class="text-xl font-bold">Edit Registrasi</h2>
            <button onclick="closeEditModal()" class="text-white text-2xl font-bold hover:text-blue-200">&times;</button>
        </div>
        <form id="editForm" class="p-6 space-y-4">
            <input type="hidden" id="registrationId">
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Status</label>
                <select id="statusSelect" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="attended">Attended</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Catatan Admin</label>
                <textarea id="adminNotesInput" rows="3" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Tambahkan catatan..."></textarea>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeEditModal()" class="flex-1 px-4 py-2 border border-slate-300 text-slate-700 rounded-lg font-medium hover:bg-slate-50 transition">Batal</button>
                <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    const API_BASE = '/api';
    let registrations = [];

    function loadRegistrations() {
        const eventId = document.getElementById('eventFilter').value;
        const statusFilter = document.getElementById('statusFilter').value;

        if (!eventId) {
            alert('Pilih acara terlebih dahulu');
            return;
        }

        fetch(`${API_BASE}/event-registrations?event_id=${eventId}`, {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            registrations = data.data || [];
            
            // Filter by status jika dipilih
            if (statusFilter) {
                registrations = registrations.filter(r => r.status === statusFilter);
            }
            
            renderTable();
            updateStatistics();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal memuat data registrasi');
        });
    }

    function renderTable() {
        const tbody = document.getElementById('registrationsTableBody');
        
        if (registrations.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="px-6 py-4 text-center text-slate-500">Tidak ada data registrasi</td></tr>';
            return;
        }

        tbody.innerHTML = registrations.map(reg => `
            <tr class="hover:bg-slate-50">
                <td class="px-6 py-4 text-sm text-slate-900">${reg.name}</td>
                <td class="px-6 py-4 text-sm text-slate-600">${reg.email}</td>
                <td class="px-6 py-4 text-sm text-slate-600">${reg.phone}</td>
                <td class="px-6 py-4 text-sm text-slate-600">${reg.institution || '-'}</td>
                <td class="px-6 py-4 text-sm">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold ${getStatusBadgeClass(reg.status)}">
                        ${getStatusLabel(reg.status)}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-slate-600">${new Date(reg.registered_at).toLocaleDateString('id-ID')}</td>
                <td class="px-6 py-4 text-center">
                    <div class="flex justify-center gap-2">
                        <button onclick="openEditModal(${reg.event_registration_id})" class="text-blue-600 hover:text-blue-800" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteRegistration(${reg.event_registration_id})" class="text-red-600 hover:text-red-800" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    }

    function updateStatistics() {
        const eventId = document.getElementById('eventFilter').value;
        
        fetch(`${API_BASE}/event-registrations?event_id=${eventId}`, {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const regs = data.data || [];
            
            document.getElementById('totalCount').textContent = regs.length;
            document.getElementById('pendingCount').textContent = regs.filter(r => r.status === 'pending').length;
            document.getElementById('confirmedCount').textContent = regs.filter(r => r.status === 'confirmed').length;
            document.getElementById('attendedCount').textContent = regs.filter(r => r.status === 'attended').length;
        });
    }

    function getStatusLabel(status) {
        const labels = {
            'pending': 'Pending',
            'confirmed': 'Confirmed',
            'attended': 'Attended',
            'cancelled': 'Cancelled'
        };
        return labels[status] || status;
    }

    function getStatusBadgeClass(status) {
        const classes = {
            'pending': 'bg-yellow-100 text-yellow-800',
            'confirmed': 'bg-blue-100 text-blue-800',
            'attended': 'bg-green-100 text-green-800',
            'cancelled': 'bg-red-100 text-red-800'
        };
        return classes[status] || 'bg-gray-100 text-gray-800';
    }

    function openEditModal(id) {
        const registration = registrations.find(r => r.event_registration_id === id);
        if (!registration) return;

        document.getElementById('registrationId').value = id;
        document.getElementById('statusSelect').value = registration.status;
        document.getElementById('adminNotesInput').value = registration.admin_notes || '';
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    document.getElementById('editForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const id = document.getElementById('registrationId').value;
        const status = document.getElementById('statusSelect').value;
        const admin_notes = document.getElementById('adminNotesInput').value;

        fetch(`${API_BASE}/event-registrations/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('token')}`,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                status: status,
                admin_notes: admin_notes
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                alert(data.message);
                closeEditModal();
                loadRegistrations();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal mengupdate registrasi');
        });
    });

    function deleteRegistration(id) {
        if (!confirm('Yakin ingin menghapus registrasi ini?')) return;

        fetch(`${API_BASE}/event-registrations/${id}`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                alert(data.message);
                loadRegistrations();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal menghapus registrasi');
        });
    }

    function exportToCSV() {
        const eventId = document.getElementById('eventFilter').value;
        if (!eventId) {
            alert('Pilih acara terlebih dahulu');
            return;
        }

        if (registrations.length === 0) {
            alert('Tidak ada data untuk diexport');
            return;
        }

        let csv = 'Nama,Email,Telepon,Institusi,Posisi,Status,Catatan Admin,Tgl Daftar\n';
        
        registrations.forEach(reg => {
            csv += `"${reg.name}","${reg.email}","${reg.phone}","${reg.institution || ''}","${reg.position || ''}","${reg.status}","${(reg.admin_notes || '').replace(/"/g, '""')}","${new Date(reg.registered_at).toLocaleDateString('id-ID')}"\n`;
        });

        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        
        link.setAttribute('href', url);
        link.setAttribute('download', `registrasi-acara-${eventId}-${new Date().toISOString().split('T')[0]}.csv`);
        link.style.visibility = 'hidden';
        
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    // Load registrations on page load
    window.addEventListener('load', () => {
        document.getElementById('eventFilter').addEventListener('change', loadRegistrations);
        document.getElementById('statusFilter').addEventListener('change', loadRegistrations);
    });
</script>
@endsection
