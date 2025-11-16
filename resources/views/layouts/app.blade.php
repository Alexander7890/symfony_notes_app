<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
    <style>
        body { background:#f5f5f5; font-family: 'Segoe UI', Arial, sans-serif; margin:0; }
        .container { max-width: 960px; margin: 40px auto; background:#fff; border-radius: 10px; padding: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
        .row { display:flex; flex-wrap:wrap; justify-content:space-between; align-items:center; gap: 10px; }
        .space { margin-bottom: 18px; }
        .primary { background:#2563eb; color:#fff; border:none; padding:10px 18px; border-radius:6px; cursor:pointer; }
        .primary:hover { background:#1d4ed8; }
        table { width:100%; border-collapse:collapse; margin-top:12px; }
        th, td { padding:10px; border-bottom:1px solid #e5e7eb; text-align:left; }
        th { background:#f0f4ff; text-transform:uppercase; font-size:12px; letter-spacing:.05em; }
        .badge { display:inline-block; padding:3px 10px; border-radius:999px; background:#eef2ff; color:#4338ca; font-size:12px; }
        input, select, textarea { padding:8px 10px; border-radius:6px; border:1px solid #d1d5db; width:100%; box-sizing:border-box; }
        form.inline { display:inline; }
        .alert { padding:12px 16px; border-radius:6px; margin-bottom:16px; }
        .alert-success { background:#ecfdf5; color:#047857; border:1px solid #a7f3d0; }
        .alert-danger { background:#fef2f2; color:#b91c1c; border:1px solid #fecaca; }
        button { cursor:pointer; }
        .modal { position:fixed; inset:0; background:rgba(15,23,42,0.5); display:none; align-items:center; justify-content:center; }
        .modal.show { display:flex; }
        .modal-card { background:#fff; padding:20px; border-radius:8px; min-width:320px; }
    </style>
</head>
<body>
<div class="container">
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    @yield('content')
</div>
<div id="modal" class="modal">
    <div class="modal-card">
        <h3 id="modal-title">Confirm</h3>
        <div id="modal-body" style="margin:12px 0"></div>
        <div style="display:flex;justify-content:flex-end;gap:10px;">
            <button type="button" onclick="Modal.close()">Cancel</button>
            <button type="button" class="primary" id="modal-confirm">Confirm</button>
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
        const btn = document.getElementById('modal-confirm');
        btn.onclick = () => {
            if (this.form) {
                this.form.submit();
            }
            this.close();
        };
    },
    close() {
        document.getElementById('modal').classList.remove('show');
        this.form = null;
    },
    confirm(message, form) {
        this.open(message, form);
        return false;
    }
};
</script>
</body>
</html>
