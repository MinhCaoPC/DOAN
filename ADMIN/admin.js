// ADMIN/admin.js

// === KHAI BÁO BIẾN TOÀN CỤC ===
let currentLocationList = [];
let currentUserList = [];
let currentResortList = [];

// ==========================================
// 1. DASHBOARD
// ==========================================
async function loadDashboardData() {
    try {
        const response = await fetch('ADMIN/dashboard.php');
        const result = await response.json();

        if (result.status === 'success') {
            const data = result.data;
            
            // Gán dữ liệu vào các thẻ HTML tương ứng
            document.getElementById('stat-tours').textContent = data.total_tours || 0;
            document.getElementById('stat-bookings').textContent = data.total_bookings || 0;
            document.getElementById('stat-accounts').textContent = data.total_accounts || 0;
            document.getElementById('stat-contacts').textContent = data.total_contacts || 0;
            
            document.getElementById('stat-diadanh').textContent = data.total_diadanh || 0;
            document.getElementById('stat-monan').textContent = data.total_monan || 0;
            document.getElementById('stat-resorts').textContent = data.total_resorts || 0;
        } else {
            console.error("Lỗi Dashboard:", result.message);
        }
    } catch (error) {
        console.error('Lỗi kết nối Dashboard:', error);
    }
}

// ==========================================
// 2. QUẢN LÝ TÀI KHOẢN (USER)
// ==========================================
async function loadUsersData() {
    const tableBody = document.querySelector('#users table tbody');
    tableBody.innerHTML = '<tr><td colspan="6" class="text-center">Đang tải...</td></tr>';

    try {
        const response = await fetch('ADMIN/admin.php?action=read');
        const result = await response.json();

        tableBody.innerHTML = '';
        if (result.status === 'success') {
            currentUserList = result.data; // Lưu dữ liệu vào biến toàn cục

            if (result.data.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="6" class="text-center">Chưa có tài khoản nào.</td></tr>';
                return;
            }

            result.data.forEach((user, index) => {
                // Tạo nhãn phân biệt Admin/Khách
                let badge = user.LoaiTaiKhoan === 'AD'
                    ? '<span class="badge bg-danger">Admin</span>'
                    : '<span class="badge bg-success">Khách</span>';

                const row = `
                <tr>
                    <td>${user.MaSoTK}</td>
                    <td>
                        <strong>${user.TenTaiKhoan}</strong> <br>
                        ${badge}
                    </td>
                    <td>${user.Email}</td>
                    <td>${user.HoVaTen || '-'}</td>
                    <td>${user.SDT || '-'}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="openEditUserModal(${index})">
                            <i class="fas fa-edit"></i> Sửa
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="deleteUser('${user.MaSoTK}')">
                            <i class="fas fa-trash"></i> Xoá
                        </button>
                    </td>
                </tr>`;
                tableBody.insertAdjacentHTML('beforeend', row);
            });
        } else {
            tableBody.innerHTML = `<tr><td colspan="6" class="text-danger">${result.message}</td></tr>`;
        }
    } catch (error) {
        console.error('Lỗi Users:', error);
        tableBody.innerHTML = `<tr><td colspan="6" class="text-danger">Lỗi kết nối server.</td></tr>`;
    }
}

async function addUser() {
    const form = document.getElementById('addUserForm');
    const btn = document.getElementById('btnSaveUser');

    btn.disabled = true; btn.textContent = 'Đang lưu...';

    try {
        const formData = new FormData(form);
        const response = await fetch('ADMIN/admin.php?action=add', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();

        if (result.status === 'success') {
            alert(result.message);
            form.reset();
            bootstrap.Modal.getInstance(document.getElementById('addUserModal')).hide();
            loadUsersData();
        } else {
            alert('Lỗi: ' + result.message);
        }
    } catch (error) {
        alert('Lỗi kết nối: ' + error);
    } finally {
        btn.disabled = false; btn.textContent = 'Lưu tài khoản';
    }
}

function openEditUserModal(index) {
    const user = currentUserList[index];
    if (!user) return;

    document.getElementById('editMaSoTK').value = user.MaSoTK;
    document.getElementById('editTenTaiKhoan').value = user.TenTaiKhoan;

    // Chọn đúng loại tài khoản
    document.getElementById('editLoaiTaiKhoan').value = user.LoaiTaiKhoan || 'KH';

    document.getElementById('editEmail').value = user.Email;
    document.getElementById('editHoVaTen').value = user.HoVaTen;
    document.getElementById('editSDT').value = user.SDT;

    new bootstrap.Modal(document.getElementById('editUserModal')).show();
}

async function updateUser() {
    const form = document.getElementById('editUserForm');
    const btn = document.getElementById('btnUpdateUser');

    btn.disabled = true; btn.textContent = 'Đang lưu...';

    try {
        const formData = new FormData(form);
        const response = await fetch('ADMIN/admin.php?action=update', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();

        if (result.status === 'success') {
            alert(result.message);
            bootstrap.Modal.getInstance(document.getElementById('editUserModal')).hide();
            loadUsersData();
        } else {
            alert('Lỗi: ' + result.message);
        }
    } catch (error) {
        alert('Lỗi kết nối: ' + error);
    } finally {
        btn.disabled = false; btn.textContent = 'Lưu thay đổi';
    }
}

async function deleteUser(maSoTK) {
    if (!confirm(`Xóa tài khoản ${maSoTK}?`)) return;
    try {
        const formData = new FormData();
        formData.append('MaSoTK', maSoTK);
        const response = await fetch('ADMIN/admin.php?action=delete', { method: 'POST', body: formData });
        const result = await response.json();
        if (result.status === 'success') loadUsersData();
        else alert(result.message);
    } catch (error) { alert('Lỗi: ' + error); }
}

// ==========================================
// 3. QUẢN LÝ ĐỊA DANH (LOCATIONS)
// ==========================================
async function loadLocationsData() {
    const cardBox = document.querySelector('#locations .card-box');
    cardBox.innerHTML = `
        <h4>Danh sách Địa danh</h4>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th><th>Ảnh</th><th>Tên Địa danh</th><th>Loại</th><th>Địa chỉ</th><th>Hành động</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>`;
    const tableBody = cardBox.querySelector('tbody');

    try {
        const response = await fetch('ADMIN/add_diadanh.php?action=read');
        const result = await response.json();

        tableBody.innerHTML = '';
        if (result.status === 'success') {
            currentLocationList = result.data;
            result.data.forEach((location, index) => {
                const imgTag = location.ImageDD
                    ? `<img src="${location.ImageDD}" style="height: 50px; width: 80px; object-fit: cover;">`
                    : 'Không có ảnh';
                const row = `
                    <tr>
                        <td>${location.MaDD}</td>
                        <td>${imgTag}</td> 
                        <td><strong>${location.TenDD}</strong></td>
                        <td>${location.LoaiDD || 'N/A'}</td>
                        <td>${location.DiaChiDD || 'N/A'}</td>
                        <td>
                            <button class="btn btn-sm btn-warning" onclick="openEditLocationModal(${index})"><i class="fas fa-edit"></i> Sửa</button>
                            <button class="btn btn-sm btn-danger" onclick="deleteLocation('${location.MaDD}', '${location.ImageDD}')"><i class="fas fa-trash"></i> Xoá</button>
                        </td>
                    </tr>`;
                tableBody.insertAdjacentHTML('beforeend', row);
            });
        } else {
            tableBody.innerHTML = `<tr><td colspan="6" class="text-center">${result.message}</td></tr>`;
        }
    } catch (error) {
        console.error('Lỗi Locations:', error);
        tableBody.innerHTML = `<tr><td colspan="6" class="text-center text-danger">Lỗi kết nối server.</td></tr>`;
    }
}

async function addLocation() {
    const form = document.getElementById('addLocationForm');
    const submitBtn = document.getElementById('submitLocationBtn');
    const formData = new FormData(form);

    submitBtn.disabled = true; submitBtn.textContent = 'Đang xử lý...';

    try {
        const response = await fetch('ADMIN/add_diadanh.php?action=add', { method: 'POST', body: formData });
        const result = await response.json();
        if (result.status === 'success') {
            alert(result.message);
            form.reset();
            bootstrap.Modal.getInstance(document.getElementById('addLocationModal')).hide();
            loadLocationsData();
        } else {
            alert('Lỗi: ' + result.message);
        }
    } catch (error) {
        console.error(error); alert('Lỗi kết nối server.');
    } finally {
        submitBtn.disabled = false; submitBtn.textContent = 'Lưu Địa danh';
    }
}

function openEditLocationModal(index) {
    const data = currentLocationList[index];
    if (!data) return;

    document.getElementById('editMaDD').value = data.MaDD;
    document.getElementById('editTenDD').value = data.TenDD;
    document.getElementById('editLoaiDD').value = data.LoaiDD;
    document.getElementById('editDiaChiDD').value = data.DiaChiDD;
    document.getElementById('editMapLinkDD').value = data.MapLinkDD;
    document.getElementById('editMoTaDD').value = data.MoTaDD;
    document.getElementById('editImageDD').value = "";

    const imgPreview = document.getElementById('previewEditImage');
    if (data.ImageDD) {
        imgPreview.src = data.ImageDD;
        imgPreview.style.display = 'block';
    } else {
        imgPreview.style.display = 'none';
    }
    new bootstrap.Modal(document.getElementById('editLocationModal')).show();
}

async function updateLocation() {
    const form = document.getElementById('editLocationForm');
    const submitBtn = document.getElementById('submitEditLocationBtn');
    const formData = new FormData(form);

    submitBtn.disabled = true; submitBtn.textContent = 'Đang lưu...';

    try {
        const response = await fetch('ADMIN/add_diadanh.php?action=update', { method: 'POST', body: formData });
        const result = await response.json();
        if (result.status === 'success') {
            alert(result.message);
            bootstrap.Modal.getInstance(document.getElementById('editLocationModal')).hide();
            loadLocationsData();
        } else {
            alert('Lỗi: ' + result.message);
        }
    } catch (error) {
        console.error(error); alert('Lỗi khi cập nhật.');
    } finally {
        submitBtn.disabled = false; submitBtn.textContent = 'Lưu thay đổi';
    }
}

async function deleteLocation(maDD, imagePath) {
    if (!confirm(`Xóa Địa danh ID ${maDD}?`)) return;
    try {
        const formData = new FormData();
        formData.append('MaDD', maDD);
        formData.append('ImagePath', imagePath);
        const response = await fetch('ADMIN/add_diadanh.php?action=delete', { method: 'POST', body: formData });
        const result = await response.json();
        if (result.status === 'success') {
            alert(result.message); loadLocationsData();
        } else {
            alert('Lỗi: ' + result.message);
        }
    } catch (error) { alert('Lỗi: ' + error); }
}

// ==========================================
// 4. QUẢN LÝ NGHỈ DƯỠNG (RESORT)
// ==========================================
async function loadResortsData() {
    const cardBox = document.querySelector('#resort .card-box');
    cardBox.innerHTML = `
        <h4>Danh sách Nghỉ Dưỡng</h4>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr><th>ID</th> <th>Ảnh</th> <th>Tên KND</th> <th>Loại</th> <th>Địa chỉ</th> <th>Hành động</th></tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>`;
    const tableBody = cardBox.querySelector('tbody');

    try {
        const response = await fetch('ADMIN/add_nghiduong.php?action=read');
        const result = await response.json();

        if (result.status === 'success') {
            currentResortList = result.data;
            tableBody.innerHTML = '';
            result.data.forEach((item, index) => {
                const imgTag = item.ImageKND ? `<img src="${item.ImageKND}" style="height: 50px; width: 80px; object-fit: cover;">` : '';
                const row = `
                    <tr>
                        <td>${item.MaKND}</td>
                        <td>${imgTag}</td>
                        <td><strong>${item.TenKND}</strong></td>
                        <td>${item.LoaiKHD || 'N/A'}</td>
                        <td>${item.DiaChiKND || ''}</td>
                        <td>
                            <button class="btn btn-sm btn-warning" onclick="openEditResortModal(${index})"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger" onclick="deleteResort('${item.MaKND}', '${item.ImageKND}')"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>`;
                tableBody.insertAdjacentHTML('beforeend', row);
            });
        } else {
            tableBody.innerHTML = `<tr><td colspan="6">${result.message}</td></tr>`;
        }
    } catch (err) { console.error(err); }
}

async function addResort() {
    const form = document.getElementById('addResortForm');
    const btn = document.getElementById('submitResortBtn');
    btn.disabled = true; btn.textContent = 'Đang lưu...';
    try {
        const res = await fetch('ADMIN/add_nghiduong.php?action=add', { method: 'POST', body: new FormData(form) });
        const json = await res.json();
        alert(json.message);
        if (json.status === 'success') {
            form.reset();
            bootstrap.Modal.getInstance(document.getElementById('addResortModal')).hide();
            loadResortsData();
        }
    } catch (e) { alert('Lỗi kết nối server'); }
    finally { btn.disabled = false; btn.textContent = 'Lưu lại'; }
}

function openEditResortModal(index) {
    const data = currentResortList[index];
    if (!data) return;
    document.getElementById('editMaKND').value = data.MaKND;
    document.getElementById('editTenKND').value = data.TenKND;
    document.getElementById('editLoaiKHD').value = data.LoaiKHD;
    document.getElementById('editDiaChiKND').value = data.DiaChiKND;
    document.getElementById('editMapLinkKND').value = data.MapLinkKND;
    document.getElementById('editMoTaKND').value = data.MoTaKND;
    document.getElementById('editImageKND').value = '';
    const imgPre = document.getElementById('previewEditResortImg');
    if (data.ImageKND) { imgPre.src = data.ImageKND; imgPre.style.display = 'block'; }
    else imgPre.style.display = 'none';
    new bootstrap.Modal(document.getElementById('editResortModal')).show();
}

async function updateResort() {
    const form = document.getElementById('editResortForm');
    const btn = document.getElementById('submitEditResortBtn');
    btn.disabled = true; btn.textContent = 'Đang lưu...';
    try {
        const res = await fetch('ADMIN/add_nghiduong.php?action=update', { method: 'POST', body: new FormData(form) });
        const json = await res.json();
        alert(json.message);
        if (json.status === 'success') {
            bootstrap.Modal.getInstance(document.getElementById('editResortModal')).hide();
            loadResortsData();
        }
    } catch (e) { alert('Lỗi: ' + e); }
    finally { btn.disabled = false; btn.textContent = 'Lưu thay đổi'; }
}

async function deleteResort(id, imgPath) {
    if (!confirm('Xóa Khu nghỉ dưỡng ID ' + id + '?')) return;
    try {
        const fd = new FormData(); fd.append('MaKND', id); fd.append('ImagePath', imgPath);
        const res = await fetch('ADMIN/add_nghiduong.php?action=delete', { method: 'POST', body: fd });
        const json = await res.json();
        alert(json.message);
        if (json.status === 'success') loadResortsData();
    } catch (e) { alert('Lỗi: ' + e); }
}

// ==========================================
// 5. CÁC HÀM KHÁC (LIÊN HỆ, ĐIỀU HƯỚNG)
// ==========================================
async function loadContactsData() {
    try {
        const response = await fetch('ADMIN/nhanlienhe.php');
        const result = await response.json();
        const contactList = document.getElementById('contact-list');
        contactList.innerHTML = '';

        if (result.status === 'success' && result.data.length > 0) {
            result.data.forEach(contact => {
                const time = new Date(contact.ThoiGianTao).toLocaleString('vi-VN');
                const row = `
                <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">${contact.HoVaTenTuVan} - ${contact.ChuDeQuanTam}</h5>
                        <small>${time}</small>
                    </div>
                    <p class="mb-1">${contact.EmailTuVan} | ${contact.SoDienThoaiTuVan}</p>
                </a>`;
                contactList.insertAdjacentHTML('beforeend', row);
            });
        } else {
            contactList.innerHTML = `<div class="alert alert-warning">Không có yêu cầu tư vấn mới.</div>`;
        }
    } catch (error) {
        console.error('Lỗi Contacts:', error);
    }
}
let currentFoodList = [];

async function loadFoodsData() {
    const tableBody = document.querySelector('#food table tbody');
    tableBody.innerHTML = '<tr><td colspan="6" class="text-center">Đang tải...</td></tr>';

    try {
        const response = await fetch('ADMIN/add_food.php?action=read');
        const result = await response.json();

        if (result.status === 'success') {
            currentFoodList = result.data;
            tableBody.innerHTML = '';

            if (result.data.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="6" class="text-center">Chưa có dữ liệu.</td></tr>';
                return;
            }

            result.data.forEach((item, index) => {
                // Hiển thị ảnh (Lưu ý đường dẫn có thể cần chỉnh tùy vào vị trí file admin.html)
                // Vì file admin.html ở ADMIN/, ảnh ở Pic/ (ngang hàng ADMIN), nên src="../" không chạy được nếu mở trực tiếp file.
                // Nhưng nếu chạy localhost thì src="pic/..." là ổn nếu root là thư mục cha.
                // Để an toàn, hiển thị đúng path trong DB

                const imgTag = item.ImageLinkMonAn
                    ? `<img src="${item.ImageLinkMonAn}" style="height: 50px; width: 70px; object-fit: cover; border-radius: 4px;">`
                    : '<span class="text-muted">No IMG</span>';

                const row = `
                <tr>
                    <td>${item.MaMonAn}</td>
                    <td>${imgTag}</td>
                    <td><strong>${item.TenMonAn}</strong></td>
                    <td>${item.LoaiMonAn}</td>
                    <td>${item.GiaMonAn || '-'}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="openEditFoodModal(${index})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="deleteFood('${item.MaMonAn}', '${item.ImageLinkMonAn}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>`;
                tableBody.insertAdjacentHTML('beforeend', row);
            });
        } else {
            tableBody.innerHTML = `<tr><td colspan="6" class="text-danger">${result.message}</td></tr>`;
        }
    } catch (error) {
        console.error('Lỗi Food:', error);
    }
}

async function addFood() {
    const form = document.getElementById('addFoodForm');
    const btn = document.getElementById('btnSaveFood');
    btn.disabled = true; btn.textContent = 'Đang lưu...';

    try {
        const formData = new FormData(form);
        const response = await fetch('ADMIN/add_food.php?action=add', { method: 'POST', body: formData });
        const result = await response.json();

        if (result.status === 'success') {
            alert(result.message);
            form.reset();
            bootstrap.Modal.getInstance(document.getElementById('addFoodModal')).hide();
            loadFoodsData();
        } else {
            alert('Lỗi: ' + result.message);
        }
    } catch (e) { alert('Lỗi: ' + e); }
    finally { btn.disabled = false; btn.textContent = 'Lưu Món ăn'; }
}

function openEditFoodModal(index) {
    const data = currentFoodList[index];
    if (!data) return;

    document.getElementById('editMaMonAn').value = data.MaMonAn;
    document.getElementById('editTenMonAn').value = data.TenMonAn;
    document.getElementById('editLoaiMonAn').value = data.LoaiMonAn;
    document.getElementById('editGiaMonAn').value = data.GiaMonAn;
    document.getElementById('editDiaChiMonAn').value = data.DiaChiMonAn;
    document.getElementById('editMapLinkMonAn').value = data.MapLinkMonAn;
    document.getElementById('editMoTaMonAn').value = data.MoTaMonAn;

    const imgPre = document.getElementById('previewEditFood');
    if (data.ImageLinkMonAn) {
        imgPre.src = data.ImageLinkMonAn;
        imgPre.style.display = 'block';
    } else {
        imgPre.style.display = 'none';
    }

    new bootstrap.Modal(document.getElementById('editFoodModal')).show();
}

async function updateFood() {
    const form = document.getElementById('editFoodForm');
    const btn = document.getElementById('btnUpdateFood');
    btn.disabled = true; btn.textContent = 'Đang lưu...';

    try {
        const formData = new FormData(form);
        const response = await fetch('ADMIN/add_food.php?action=update', { method: 'POST', body: formData });
        const result = await response.json();

        if (result.status === 'success') {
            alert(result.message);
            bootstrap.Modal.getInstance(document.getElementById('editFoodModal')).hide();
            loadFoodsData();
        } else {
            alert('Lỗi: ' + result.message);
        }
    } catch (e) { alert('Lỗi: ' + e); }
    finally { btn.disabled = false; btn.textContent = 'Lưu thay đổi'; }
}

async function deleteFood(id, imgPath) {
    if (!confirm('Bạn có chắc muốn xóa món ăn này không?')) return;
    try {
        const formData = new FormData();
        formData.append('MaMonAn', id);
        formData.append('ImagePath', imgPath);
        const response = await fetch('ADMIN/add_food.php?action=delete', { method: 'POST', body: formData });
        const result = await response.json();

        if (result.status === 'success') {
            alert(result.message);
            loadFoodsData();
        } else {
            alert('Lỗi: ' + result.message);
        }
    } catch (e) { alert('Lỗi: ' + e); }
}

async function loadToursData() {
    const tableBody = document.querySelector('#tours table tbody');
    tableBody.innerHTML = '<tr><td colspan="7" class="text-center">Đang tải...</td></tr>';

    try {
        const response = await fetch('ADMIN/add_tour.php?action=read');
        const result = await response.json();

        if (result.status === 'success') {
            currentTourList = result.data;
            tableBody.innerHTML = '';

            if (result.data.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="7" class="text-center">Chưa có tour nào.</td></tr>';
                return;
            }

            result.data.forEach((item, index) => {
                const imgTag = item.ImageTourMain
                    ? `<img src="${item.ImageTourMain}" style="height: 60px; width: 90px; object-fit: cover; border-radius: 4px;">`
                    : '<span class="text-muted">No IMG</span>';

                // Format giá tiền Việt Nam
                const price = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(item.GiaTour);

                const badge = item.LaNoiBat == 1
                    ? '<span class="badge bg-danger"><i class="fas fa-star"></i> HOT</span>'
                    : '';

                const row = `
                <tr>
                    <td>${item.MaTour}</td>
                    <td>${imgTag}</td>
                    <td>
                        <strong>${item.TenTour}</strong><br>
                        <small class="text-muted">${item.DoiTuong || ''}</small>
                    </td>
                    <td class="text-danger fw-bold">${price}</td>
                    <td>${item.ThoiGianTour || ''}</td>
                    <td>${badge}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="openEditTourModal(${index})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="deleteTour('${item.MaTour}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>`;
                tableBody.insertAdjacentHTML('beforeend', row);
            });
        } else {
            tableBody.innerHTML = `<tr><td colspan="7" class="text-danger">${result.message}</td></tr>`;
        }
    } catch (error) {
        console.error('Lỗi Tour:', error);
    }
}

async function addTour() {
    const form = document.getElementById('addTourForm');
    const btn = document.getElementById('btnSaveTour');
    btn.disabled = true; btn.textContent = 'Đang lưu...';

    try {
        const formData = new FormData(form);
        // Checkbox nếu không tick thì không gửi value, nên xử lý bên PHP hoặc append thủ công nếu cần
        // Tuy nhiên PHP isset() xử lý tốt việc này.

        const response = await fetch('ADMIN/add_tour.php?action=add', { method: 'POST', body: formData });
        const result = await response.json();

        if (result.status === 'success') {
            alert(result.message);
            form.reset();
            bootstrap.Modal.getInstance(document.getElementById('addTourModal')).hide();
            loadToursData();
        } else {
            alert('Lỗi: ' + result.message);
        }
    } catch (e) { alert('Lỗi: ' + e); }
    finally { btn.disabled = false; btn.textContent = 'Lưu Tour'; }
}

function openEditTourModal(index) {
    const data = currentTourList[index];
    if (!data) return;

    document.getElementById('editMaTour').value = data.MaTour;
    document.getElementById('editTenTour').value = data.TenTour;
    document.getElementById('editGiaTour').value = data.GiaTour;
    document.getElementById('editThoiGianTour').value = data.ThoiGianTour;
    document.getElementById('editDoiTuong').value = data.DoiTuong;
    document.getElementById('editKhachSan').value = data.KhachSan;
    document.getElementById('editMoTaTour').value = data.MoTaTour;
    document.getElementById('editLichTrinhTour').value = data.LichTrinhTour;

    // Checkbox
    document.getElementById('editLaNoiBat').checked = (data.LaNoiBat == 1);

    // Preview ảnh
    const imgMain = document.getElementById('previewEditTourMain');
    if (data.ImageTourMain) { imgMain.src = data.ImageTourMain; imgMain.style.display = 'block'; }
    else imgMain.style.display = 'none';

    const imgSub = document.getElementById('previewEditTourSub');
    if (data.ImageTourSub) { imgSub.src = data.ImageTourSub; imgSub.style.display = 'block'; }
    else imgSub.style.display = 'none';

    new bootstrap.Modal(document.getElementById('editTourModal')).show();
}

async function updateTour() {
    const form = document.getElementById('editTourForm');
    const btn = document.getElementById('btnUpdateTour');
    btn.disabled = true; btn.textContent = 'Đang lưu...';

    try {
        const formData = new FormData(form);
        const response = await fetch('ADMIN/add_tour.php?action=update', { method: 'POST', body: formData });
        const result = await response.json();

        if (result.status === 'success') {
            alert(result.message);
            bootstrap.Modal.getInstance(document.getElementById('editTourModal')).hide();
            loadToursData();
        } else {
            alert('Lỗi: ' + result.message);
        }
    } catch (e) { alert('Lỗi: ' + e); }
    finally { btn.disabled = false; btn.textContent = 'Lưu thay đổi'; }
}

async function deleteTour(id) {
    if (!confirm('Bạn có chắc muốn xóa Tour này?')) return;
    try {
        const formData = new FormData();
        formData.append('MaTour', id);
        const response = await fetch('ADMIN/add_tour.php?action=delete', { method: 'POST', body: formData });
        const result = await response.json();

        if (result.status === 'success') {
            alert(result.message);
            loadToursData();
        } else {
            alert('Lỗi: ' + result.message);
        }
    } catch (e) { alert('Lỗi: ' + e); }
}

// ... (Code cũ giữ nguyên)

// ==========================================
// 8. QUẢN LÝ ĐẶT TOUR (BOOKING)
// ==========================================
let currentBookingList = [];

async function loadBookingData() {
    const tableBody = document.querySelector('#booking table tbody');
    tableBody.innerHTML = '<tr><td colspan="6" class="text-center">Đang tải...</td></tr>';

    try {
        const response = await fetch('ADMIN/booking_manager.php?action=read');
        const result = await response.json();

        if (result.status === 'success') {
            currentBookingList = result.data;
            tableBody.innerHTML = '';

            if (result.data.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="6" class="text-center">Chưa có đơn đặt tour nào.</td></tr>';
                return;
            }

            result.data.forEach((item, index) => {
                // Format tiền
                const total = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(item.TongTien);
                const dateBook = new Date(item.ThoiGian).toLocaleString('vi-VN');

                // Xử lý trạng thái & Badge
                let statusBadge = '';
                let actionBtns = '';

                // Logic hiển thị trạng thái
                switch (item.TrangThai) {
                    case 'CXN':
                        statusBadge = '<span class="badge bg-warning text-dark">Chờ xác nhận</span>';
                        // Nút duyệt nhanh
                        actionBtns += `<button class="btn btn-sm btn-success me-1" onclick="quickUpdateStatus('${item.MaDatTour}', 'TC')" title="Duyệt ngay"><i class="fas fa-check"></i></button>`;
                        break;
                    case 'TC':
                        if (item.CanLuuY == 1) {
                            statusBadge = '<span class="badge bg-success">Thành công</span> <span class="badge bg-danger" title="Hệ thống tự duyệt"><i class="fas fa-robot"></i> Auto</span>';
                        } else {
                            statusBadge = '<span class="badge bg-success">Thành công</span>';
                        }
                        break;
                    case 'YCH':
                        statusBadge = '<span class="badge bg-danger">Yêu cầu hủy</span>';
                        actionBtns += `<button class="btn btn-sm btn-outline-danger me-1" onclick="quickUpdateStatus('${item.MaDatTour}', 'DH')" title="Xác nhận hủy"><i class="fas fa-times"></i> Hủy</button>`;
                        break;
                    case 'DH':
                        statusBadge = '<span class="badge bg-secondary">Đã hủy</span>';
                        break;
                }

                const row = `
                <tr>
                    <td>${item.MaDatTour}</td>
                    <td>
                        <strong>${item.HoVaTenT}</strong><br>
                        <small>${item.SDTT}</small>
                    </td>
                    <td>
                        <div class="text-primary fw-bold text-truncate" style="max-width: 200px;">${item.TenTour || 'Tour không tồn tại'}</div>
                        <small class="text-muted">${dateBook}</small>
                    </td>
                    <td>
                        SL: <strong>${item.SoLuongKhach}</strong><br>
                        ${total}
                    </td>
                    <td>${statusBadge}</td>
                    <td>
                        ${actionBtns}
                        <button class="btn btn-sm btn-info text-white" onclick="openEditBookingModal(${index})"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-danger" onclick="deleteBooking('${item.MaDatTour}')"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>`;
                tableBody.insertAdjacentHTML('beforeend', row);
            });
        } else {
            tableBody.innerHTML = `<tr><td colspan="6" class="text-danger">${result.message}</td></tr>`;
        }
    } catch (error) {
        console.error('Lỗi Booking:', error);
    }
}

// Hàm duyệt nhanh / hủy nhanh (Không cần mở modal)
async function quickUpdateStatus(maDatTour, newStatus) {
    if (!confirm(`Bạn có chắc chắn muốn chuyển trạng thái đơn ${maDatTour} sang ${newStatus === 'TC' ? 'THÀNH CÔNG' : 'ĐÃ HỦY'}?`)) return;

    // Gọi API update nhưng chỉ gửi status
    // Lưu ý: API update hiện tại cần nhiều trường, ta sẽ dùng form ảo hoặc sửa API. 
    // Cách nhanh nhất ở đây là dùng form data nhưng chỉ điền Status, 
    // Tuy nhiên backend đang update đè. Để an toàn, ta tìm item trong list để lấy data cũ fill vào.

    const item = currentBookingList.find(x => x.MaDatTour == maDatTour);
    if (!item) return;

    const fd = new FormData();
    fd.append('MaDatTour', maDatTour);
    fd.append('TrangThai', newStatus);
    // Giữ nguyên data cũ
    fd.append('HoVaTenT', item.HoVaTenT);
    fd.append('SDTT', item.SDTT);
    fd.append('EmailT', item.EmailT);
    fd.append('DiaChiT', item.DiaChiT);
    fd.append('SoLuongKhach', item.SoLuongKhach);

    try {
        const res = await fetch('ADMIN/booking_manager.php?action=update', { method: 'POST', body: fd });
        const json = await res.json();
        if (json.status === 'success') {
            alert(json.message);
            loadBookingData();
        } else {
            alert(json.message);
        }
    } catch (e) { alert('Lỗi: ' + e); }
}

function openEditBookingModal(index) {
    const data = currentBookingList[index];
    if (!data) return;

    document.getElementById('bk_MaDatTour').value = data.MaDatTour;
    document.getElementById('bk_HoVaTenT').value = data.HoVaTenT;
    document.getElementById('bk_SDTT').value = data.SDTT;
    document.getElementById('bk_EmailT').value = data.EmailT;
    document.getElementById('bk_DiaChiT').value = data.DiaChiT;
    document.getElementById('bk_SoLuongKhach').value = data.SoLuongKhach;
    document.getElementById('bk_TrangThai').value = data.TrangThai;

    new bootstrap.Modal(document.getElementById('editBookingModal')).show();
}

async function updateBooking() {
    const form = document.getElementById('editBookingForm');
    try {
        const res = await fetch('ADMIN/booking_manager.php?action=update', { method: 'POST', body: new FormData(form) });
        const json = await res.json();
        if (json.status === 'success') {
            alert(json.message);
            bootstrap.Modal.getInstance(document.getElementById('editBookingModal')).hide();
            loadBookingData();
        } else {
            alert(json.message);
        }
    } catch (e) { alert('Lỗi: ' + e); }
}

async function deleteBooking(id) {
    if (!confirm('Bạn có chắc chắn xóa lịch sử đặt tour này? Dữ liệu sẽ không thể phục hồi.')) return;
    try {
        const fd = new FormData(); fd.append('MaDatTour', id);
        const res = await fetch('ADMIN/booking_manager.php?action=delete', { method: 'POST', body: fd });
        const json = await res.json();
        if (json.status === 'success') loadBookingData();
        else alert(json.message);
    } catch (e) { alert('Lỗi: ' + e); }
}

function showSection(sectionId, event) {

    if (event) event.preventDefault();


    document.querySelectorAll('.section-content').forEach(sec => sec.classList.remove('active'));

    const target = document.getElementById(sectionId);
    if (target) target.classList.add('active');


    document.querySelectorAll('.sidebar .nav-link').forEach(link => link.classList.remove('active'));
 
    const activeLink = document.querySelector(`.sidebar .nav-link[onclick*="'${sectionId}'"]`);
    if (activeLink) {
        activeLink.classList.add('active');
    }

    if (sectionId === 'dashboard') loadDashboardData();
    else if (sectionId === 'users') loadUsersData();
    else if (sectionId === 'contacts') loadContactsData();
    else if (sectionId === 'locations') loadLocationsData();
    else if (sectionId === 'resort') loadResortsData();
    else if (sectionId === 'food') loadFoodsData();
    else if (sectionId === 'tours') loadToursData();
    else if (sectionId === 'booking') loadBookingData();
}
// Khởi chạy mặc định
window.onload = loadDashboardData;