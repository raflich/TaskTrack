<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>TaskTrack — Welcome</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { font-family: 'Poppins', sans-serif !important; }
    </style>
</head>
<body style="margin:0; padding:0; background:#fdf6ee; overflow:hidden; height:100vh; display:flex; flex-direction:column;">

    {{-- ══ FAKE NAVBAR ══ --}}
    <nav style="height:73px; background:#fdf6ee; border-bottom:1px solid #eae0d5;
                display:flex; align-items:center; justify-content:space-between;
                padding-left:24px; padding-right:40px; flex-shrink:0; position:relative; z-index:10;">
        <span style="font-size:32px; font-weight:800; color:#7c4a24; letter-spacing:-0.025em;">TaskTrack</span>
        <div style="display:flex; align-items:center; gap:20px;">
            <button style="background:none;border:none;cursor:pointer;padding:6px;color:#8c7462;">
                <svg width="21" height="21" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </button>
            <div style="width:36px;height:36px;border-radius:9999px;background:#dbeafe;color:#1e40af;
                        display:flex;align-items:center;justify-content:center;font-size:12px;
                        font-weight:700;border:1px solid #b9d5fd;">
                ?
            </div>
        </div>
    </nav>

    {{-- ══ BODY ROW ══ --}}
    <div style="display:flex; flex:1; overflow:hidden; position:relative;">

        {{-- FAKE SIDEBAR --}}
        <aside style="width:256px; background:#fdf6ee; border-right:3px solid #eae0d5;
                      display:flex; flex-direction:column; justify-content:space-between;
                      padding:24px 16px; flex-shrink:0; z-index:10;">
            <div style="display:flex;flex-direction:column;gap:8px;">
                <div style="display:flex;align-items:center;gap:12px;padding:12px 16px;
                            border-radius:12px;background:#f97316;box-shadow:0 4px 14px rgba(249,115,22,0.25);">
                    <svg style="width:20px;height:20px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 0v10"/>
                    </svg>
                    <span style="font-size:14px;font-weight:600;color:#fff;">Kanban Board</span>
                </div>
                <div style="display:flex;align-items:center;gap:12px;padding:12px 16px;border-radius:12px;color:#8c7462;">
                    <svg style="width:20px;height:20px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    <span style="font-size:14px;font-weight:600;">List View</span>
                </div>
                <div style="display:flex;align-items:center;gap:12px;padding:12px 16px;border-radius:12px;color:#8c7462;">
                    <svg style="width:20px;height:20px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    <span style="font-size:14px;font-weight:600;">Trash</span>
                </div>
            </div>
            <a href="{{ route('login') }}"
               style="display:flex;align-items:center;gap:12px;padding:12px 16px;border-radius:12px;
                      color:#8c7462;text-decoration:none;font-size:14px;font-weight:600;"
               onmouseover="this.style.color='#f97316'"
               onmouseout="this.style.color='#8c7462'">
                <svg style="width:20px;height:20px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Login
            </a>
        </aside>

        {{-- ══ MAIN CONTENT ══ --}}
        <main style="flex:1; background:#fffbf7; position:relative; overflow:hidden;">

            {{-- Fake kanban columns (blurred preview) --}}
            <div style="padding:32px 32px 0; display:flex; gap:24px; opacity:0.35; pointer-events:none; user-select:none;">
                @foreach(['To Do', 'In Progress', 'Done'] as $col)
                <div style="flex:1;">
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:16px;">
                        <span style="font-weight:700;color:#4a270f;font-size:15px;">{{ $col }}</span>
                        <span style="background:#eae0d5;color:#8c7462;font-size:11px;padding:2px 10px;border-radius:9999px;font-weight:700;">0</span>
                    </div>
                    <div style="background:#fbf3e9;border-radius:16px;height:200px;"></div>
                </div>
                @endforeach
            </div>

            {{-- Dark overlay --}}
            <div style="position:absolute;inset:0;background:rgba(30,10,0,0.55);z-index:5;"></div>

            {{-- ══ WELCOME CARD ══ --}}
            <div style="position:absolute;inset:0;z-index:10;display:flex;align-items:center;justify-content:center;padding:24px;">
                <div style="background:#fff;border-radius:20px;padding:44px 40px 36px;
                            max-width:420px;width:100%;text-align:center;
                            box-shadow:0 20px 60px rgba(0,0,0,0.25);">

                    {{-- Rocket Icon --}}
                    <div style="width:72px;height:72px;border-radius:9999px;background:#fff7ed;
                                display:flex;align-items:center;justify-content:center;margin:0 auto 24px;
                                border:2px solid #fed7aa;">
                        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2l2-2-4-1-1 0z"/>
                            <path d="M12 15l-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"/>
                            <path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"/>
                            <path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"/>
                        </svg>
                    </div>

                    {{-- Heading --}}
                    <h1 style="font-size:22px;font-weight:800;color:#1a0a00;margin:0 0 12px;line-height:1.3;">
                        What are we working<br>on today?
                    </h1>

                    {{-- Subtitle --}}
                    <p style="font-size:13.5px;color:#9a8070;line-height:1.65;margin:0 0 28px;">
                        The board is set and ready for your next big<br>
                        achievement. Start by adding your first task.
                    </p>

                    {{-- CTA Button --}}
                    <a href="{{ route('login') }}"
                       style="display:block;background:#f97316;color:#fff;font-weight:700;font-size:15px;
                              padding:14px 24px;border-radius:12px;text-decoration:none;
                              transition:background 0.2s;"
                       onmouseover="this.style.background='#ea6c0a'"
                       onmouseout="this.style.background='#f97316'">
                        + Create First Task
                    </a>

                    {{-- Cancel --}}
                    <button onclick="window.history.back()"
                            style="display:block;width:100%;background:none;border:none;cursor:pointer;
                                   margin-top:16px;font-size:13.5px;color:#b4a090;font-weight:500;
                                   font-family:'Plus Jakarta Sans',sans-serif;transition:color 0.2s;"
                            onmouseover="this.style.color='#4a270f'"
                            onmouseout="this.style.color='#b4a090'">
                        Cancel
                    </button>
                </div>
            </div>
        </main>
    </div>

</body>
</html>
