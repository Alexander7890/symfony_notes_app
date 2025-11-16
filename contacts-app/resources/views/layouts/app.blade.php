<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Contacts')</title>
    <style>
        body { background:#f5f5f5; font-family: system-ui, -apple-system, sans-serif; }
        .container { max-width: 960px; margin: 40px auto; background:#fff; border-radius:8px; padding:24px; box-shadow:0 2px 10px rgba(0,0,0,.08); }
        .row { display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; }
        .space { margin-bottom: 18px; }
        table { width:100%; border-collapse: collapse; }
        th, td { text-align:left; padding:10px; border-bottom:1px solid #ececec; }
        .primary { background:#2563eb; border:none; color:#fff; padding:8px 14px; border-radius:6px; cursor:pointer; }
        input[type="text"], input[type="email"], textarea, select { width:100%; padding:8px; border:1px solid #d4d4d8; border-radius:6px; }
        .badge { display:inline-flex; padding:2px 10px; border-radius:999px; background:#eef2ff; color:#3730a3; font-size:12px; }
        form.inline { display:inline; }
        .modal { position:fixed; inset:0; background:rgba(0,0,0,.45); display:none; align-items:center; justify-content:center; }
        .modal.show { display:flex; }
    </style>
</head>
<body>
<div class="container">
    @yield('content')
</div>
<div id="modal" class="modal">
    <div class="card" style="background:#fff;padding:20px;border-radius:8px;min-width:300px;">
        <h3 id="modal-title" style="margin-top:0">Confirm</h3>
        <div id="modal-body" style="margin:16px 0"></div>
        <div style="display:flex;justify-content:flex-end;gap:10px;">
            <button type="button" onclick="Modal.close()">Cancel</button>
            <button type="button" id="modal-confirm" class="primary">Confirm</button>
        </div>
    </div>
</div>
<script>
const Modal = {
    form: null,
    open(message, form) {
        this.form = form;
        document.getElementById('modal-body').innerText = message;
        document.getElementById('modal').classList.add('show');
    },
    close() {
        document.getElementById('modal').classList.remove('show');
        this.form = null;
    },
    confirm(message, form) {
        this.open(message, form);
        const confirmBtn = document.getElementById('modal-confirm');
        confirmBtn.onclick = () => {
            if (this.form) {
                this.form.submit();
            }
            this.close();
        };
        return false;
    }
};
</script>
</body>
</html>
