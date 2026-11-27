// 學生基本資料（resources/views/school_stats/index.blade.php）
// 五個分頁：學校、教職員、學生數統計、班級數統計、統計。功能照 Go 版 chcschools 的 web/src/pages。
(function () {
    'use strict';

    var app = document.getElementById('ss-app');
    if (!app) return;

    var DISTRICTS = [
        '彰化市', '芬園鄉', '花壇鄉', '秀水鄉', '鹿港鎮', '福興鄉', '線西鄉',
        '和美鎮', '伸港鄉', '員林市', '社頭鄉', '永靖鄉', '埔心鄉', '溪湖鎮',
        '大村鄉', '埔鹽鄉', '田中鎮', '北斗鎮', '田尾鄉', '埤頭鄉', '溪州鄉',
        '竹塘鄉', '二林鎮', '大城鄉', '芳苑鄉', '二水鄉',
    ];
    var TYPES = [['all', '全部'], ['國民小學', '國小'], ['國民中學', '國中']];
    var ELEMENTARY = ['一年級', '二年級', '三年級', '四年級', '五年級', '六年級'];
    var JUNIOR = ['七年級', '八年級', '九年級'];
    var SPECIAL_ED = '特教班';
    var KIND_ORDER = ['普通班', '體育班', '藝才班', '才藝班', '實驗班', '特教班'];
    var TITLE_ORDER = { '校長': 0, '主任': 1, '教師兼主任': 2, '導師': 3, '專任教師': 4, '職員': 5, '護理師': 6 };
    var SMALL = 6;
    var LARGE = 12;

    var TABS = [
        { hash: 'schools', full: '學校', short: '校', data: 'schools', render: schoolsTab },
        { hash: 'staff', full: '教職員', short: '教', data: 'staff', render: staffTab },
        { hash: 'stats', full: '學生數統計', short: '學', data: 'students', render: studentsTab },
        { hash: 'class-stats', full: '班級數統計', short: '班', data: 'classes', render: classesTab },
        { hash: 'class-size-stats', full: '統計', short: '計', data: 'classes', render: sizeTab },
    ];

    // 鄉鎮市跨分頁保留（同 Go 版），其他篩選換分頁就重設
    var shared = { district: 'all' };
    var loaded = {};
    var current = -1;
    var tabsEl = document.getElementById('ss-tabs');
    var body = document.getElementById('ss-body');

    // ---------- 小工具 ----------
    function esc(s) {
        return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
        });
    }
    function blank(n) { return n ? n : ''; }
    function typeShort(t) { return t === '國民小學' ? '國小' : t === '國民中學' ? '國中' : t; }
    function isCounty(s) { return String(s.code).indexOf('074') === 0; }
    function el(html) {
        var t = document.createElement('template');
        t.innerHTML = html.trim();
        return t.content.firstChild;
    }
    function nextSort(dir) { return dir === null ? 'asc' : dir === 'asc' ? 'desc' : null; }
    function sortMark(dir) { return dir === 'asc' ? '▲' : dir === 'desc' ? '▼' : '⇅'; }
    function sortText(dir) { return dir === 'asc' ? '小到大 ▲' : dir === 'desc' ? '大到小 ▼' : '預設 ⇅'; }
    function detail(s, k) { return (s.details && s.details[k]) || null; }
    function classTotal(s, kinds) {
        var sum = 0;
        (kinds || Object.keys(s.details || {})).forEach(function (k) {
            var d = detail(s, k);
            sum += d ? d.c : 0;
        });
        return sum;
    }
    function sortKinds(kinds) {
        return kinds.slice().sort(function (a, b) {
            var ia = KIND_ORDER.indexOf(a), ib = KIND_ORDER.indexOf(b);
            if (ia === -1 && ib === -1) return a.localeCompare(b, 'zh-TW');
            if (ia === -1) return 1;
            if (ib === -1) return -1;
            return ia - ib;
        });
    }

    function load(key) {
        if (!loaded[key]) {
            var url = app.dataset.dataUrl.replace('__KEY__', key);
            loaded[key] = fetch(url, { headers: { Accept: 'application/json' } }).then(function (r) {
                if (!r.ok) throw new Error('HTTP ' + r.status);
                return r.json();
            }).catch(function (e) {
                delete loaded[key];
                throw e;
            });
        }
        return loaded[key];
    }

    // ---------- 篩選列 ----------
    // opts: { district, type, search, county, exportKey }；回傳 state 與 bar 元素
    function filterBar(opts, onChange) {
        var st = { type: 'all', q: '', county: true, nonCounty: true };
        var bar = el('<div class="d-flex flex-wrap align-items-center gap-3 mb-3"></div>');

        if (opts.district) {
            var ds = el('<select class="form-select w-auto" aria-label="篩選鄉鎮市"><option value="all">全部鄉鎮市</option>' +
                DISTRICTS.map(function (d) { return '<option>' + esc(d) + '</option>'; }).join('') + '</select>');
            ds.value = shared.district;
            ds.addEventListener('change', function () { shared.district = ds.value; onChange(); });
            bar.appendChild(ds);
            st.districtSelect = ds;
        }
        var ts = el('<select class="form-select w-auto" aria-label="篩選學校類別">' +
            TYPES.map(function (t) { return '<option value="' + t[0] + '">' + t[1] + '</option>'; }).join('') + '</select>');
        ts.addEventListener('change', function () { st.type = ts.value; onChange(); });
        bar.appendChild(ts);

        if (opts.search) {
            var group = el('<div class="input-group w-auto"><input type="search" class="form-control" placeholder="搜尋學校名稱" aria-label="搜尋學校名稱"></div>');
            var input = group.querySelector('input');
            input.addEventListener('input', function () { st.q = input.value.trim(); onChange(); });
            bar.appendChild(group);
        }
        if (opts.county) {
            [['county', '縣立'], ['nonCounty', '非縣立']].forEach(function (c) {
                var lab = el('<div class="form-check mb-0"><label class="form-check-label"><input type="checkbox" class="form-check-input" checked> ' + c[1] + '</label></div>');
                lab.querySelector('input').addEventListener('change', function (e) { st[c[0]] = e.target.checked; onChange(); });
                bar.appendChild(lab);
            });
        }
        if (opts.exportKey) {
            var btn = el('<a class="btn btn-outline-dark d-none d-md-inline-block" href="#">匯出 Excel</a>');
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                if (btn.classList.contains('disabled')) return;
                var p = new URLSearchParams({
                    district: shared.district, type: st.type, q: st.q,
                    county: st.county ? '1' : '0', noncounty: st.nonCounty ? '1' : '0',
                });
                window.location.href = app.dataset.exportUrl.replace('__KEY__', opts.exportKey) + '?' + p.toString();
            });
            bar.appendChild(btn);
            st.exportBtn = btn;
        }
        st.bar = bar;
        return st;
    }

    function applyFilter(rows, st, opts) {
        return rows.filter(function (s) {
            if (opts.district !== false && shared.district !== 'all' && s.district !== shared.district) return false;
            if (st.type !== 'all' && s.type !== st.type) return false;
            if (opts.county && (isCounty(s) ? !st.county : !st.nonCounty)) return false;
            if (st.q && s.name.indexOf(st.q) === -1) return false;
            return true;
        });
    }

    function note(updatedAt) {
        return updatedAt ? '<p class="small text-secondary mb-3">資料來源校務系統 ' + esc(updatedAt) + '</p>' : '';
    }
    var EMPTY = '<p class="text-secondary py-4 text-center">目前沒有資料。</p>';

    function mobileCard(i, title, sub, inner) {
        return '<details class="card mb-2"><summary class="card-body d-flex align-items-center py-2">' +
            '<div class="flex-grow-1 text-truncate"><div class="fw-medium text-truncate">' + esc(title) + '</div>' +
            '<div class="small text-secondary">' + sub + '</div></div>' +
            '<span class="text-secondary ms-2 ss-chev">›</span><span class="text-secondary small ms-2">' + (i + 1) + '</span>' +
            '</summary><div class="card-body border-top small">' + inner + '</div></details>';
    }

    // 寬表格：凍結表頭與前三欄，左右捲動箭頭
    function wideTable(theadHtml, tbodyHtml, tfootHtml) {
        var wrap = el('<div class="position-relative">' +
            '<button type="button" class="btn btn-primary ss-arrow start-0 ms-2" aria-label="向左捲動">‹</button>' +
            '<button type="button" class="btn btn-primary ss-arrow end-0 me-2" aria-label="向右捲動">›</button>' +
            '<div class="card ss-scroll"><table class="table table-sm ss-table">' +
            '<thead>' + theadHtml + '</thead><tbody>' + tbodyHtml + '</tbody>' + (tfootHtml ? '<tfoot>' + tfootHtml + '</tfoot>' : '') +
            '</table></div></div>');
        var sc = wrap.querySelector('.ss-scroll');
        wrap.querySelectorAll('.ss-arrow').forEach(function (b, i) {
            b.addEventListener('click', function () {
                sc.scrollBy({ left: (i === 0 ? -1 : 1) * sc.clientWidth * 0.8, behavior: 'smooth' });
            });
        });
        return wrap;
    }

    // 前三欄寬度依實際內容算，讓凍結的位置剛好；第二列表頭接在第一列下面
    function fixSticky(wrap) {
        var table = wrap.querySelector('table');
        if (!table || !table.offsetParent) return;
        var first = table.querySelector('thead tr');
        var c1 = first.querySelector('.sc1'), c2 = first.querySelector('.sc2');
        var w1 = c1 ? c1.getBoundingClientRect().width : 0;
        var w2 = c2 ? c2.getBoundingClientRect().width : 0;
        table.querySelectorAll('.sc1').forEach(function (c) { c.style.left = '0px'; });
        table.querySelectorAll('.sc2').forEach(function (c) { c.style.left = w1 + 'px'; });
        table.querySelectorAll('.sc3').forEach(function (c) { c.style.left = (w1 + w2) + 'px'; });
        table.style.setProperty('--ss-head1', first.getBoundingClientRect().height + 'px');
    }

    // ---------- 分頁：學校 ----------
    function schoolsTab(data) {
        var opts = { district: true, search: true };
        var st = filterBar(opts, update);
        var out = el('<div></div>');
        body.replaceChildren(st.bar, out);

        function update() {
            var rows = applyFilter(data.rows, st, opts);
            if (!rows.length) { out.innerHTML = EMPTY; return; }
            var mob = rows.map(function (s, i) {
                var dl = '<dl class="row mb-0">' +
                    (s.code ? '<dt class="col-3 fw-normal text-secondary">代碼</dt><dd class="col-9 mb-1">' + esc(s.code) + '</dd>' : '') +
                    (s.address ? '<dt class="col-3 fw-normal text-secondary">地址</dt><dd class="col-9 mb-1">' + esc(s.address) + '</dd>' : '') +
                    (s.phone ? '<dt class="col-3 fw-normal text-secondary">電話</dt><dd class="col-9 mb-1"><a href="tel:' + esc(s.phone) + '">' + esc(s.phone) + '</a></dd>' : '') +
                    '</dl><div class="d-flex gap-3 pt-1">' +
                    '<a href="' + esc(s.intro) + '" target="_blank" rel="noopener noreferrer">簡介</a>' +
                    (s.website ? '<a href="' + esc(s.website) + '" target="_blank" rel="noopener noreferrer">網站</a>' : '') + '</div>';
                return mobileCard(i, s.name, esc(s.district) + '・' + esc(typeShort(s.type)), dl);
            }).join('');
            var trs = rows.map(function (s, i) {
                return '<tr><td class="text-secondary">' + (i + 1) + '</td><td class="text-nowrap">' + esc(s.district) + '</td>' +
                    '<td class="fw-medium text-nowrap">' + esc(s.name) + '</td><td>' + esc(s.code) + '</td><td class="text-nowrap">' + esc(s.type) + '</td>' +
                    '<td class="text-nowrap">' + esc(s.address) + '</td><td class="text-nowrap">' + esc(s.phone) + '</td>' +
                    '<td><a href="' + esc(s.intro) + '" target="_blank" rel="noopener noreferrer">簡介</a></td>' +
                    '<td>' + (s.website ? '<a href="' + esc(s.website) + '" target="_blank" rel="noopener noreferrer">網站</a>' : '') + '</td></tr>';
            }).join('');
            out.innerHTML = '<div class="d-md-none">' + mob + '</div>' +
                '<div class="d-none d-md-block card"><div class="table-responsive"><table class="table table-striped table-hover mb-0 align-middle">' +
                '<thead class="table-light"><tr><th>#</th><th class="text-nowrap">鄉鎮市</th><th class="text-nowrap">學校名稱</th><th class="text-nowrap">學校代碼</th>' +
                '<th class="text-nowrap">學校類別</th><th class="text-nowrap">地址</th><th class="text-nowrap">電話</th><th>簡介</th><th>網站</th></tr></thead>' +
                '<tbody>' + trs + '</tbody></table></div></div>';
        }
        update();
    }

    // ---------- 分頁：教職員 ----------
    function staffTab(data) {
        var opts = { district: true, search: true };
        var st = filterBar(opts, update);
        var out = el('<div></div>');
        body.replaceChildren(st.bar, el('<div>' + note(data.updated_at) + '</div>'), out);

        function titles(s) {
            var keys = Object.keys(s.details || {});
            keys.sort(function (a, b) {
                var oa = a in TITLE_ORDER ? TITLE_ORDER[a] : 99, ob = b in TITLE_ORDER ? TITLE_ORDER[b] : 99;
                return oa - ob;
            });
            return '<div class="d-flex flex-wrap gap-1">' + keys.map(function (t) {
                return '<span class="border rounded bg-body-tertiary px-2 py-1 text-nowrap"><span class="fw-semibold">' + esc(t) +
                    '</span> <span class="fw-semibold text-primary">' + s.details[t] + '</span></span>';
            }).join('') + '</div>';
        }

        function update() {
            var rows = applyFilter(data.rows, st, opts);
            if (!rows.length) { out.innerHTML = EMPTY; return; }
            var mob = rows.map(function (s, i) {
                return mobileCard(i, s.name, esc(s.district) + '・男 ' + s.boy + ' / 女 ' + s.girl + '・合計 ' + s.total, titles(s));
            }).join('');
            var trs = rows.map(function (s, i) {
                return '<tr><td class="text-secondary">' + (i + 1) + '</td><td class="text-nowrap">' + esc(s.district) + '</td>' +
                    '<td class="fw-medium">' + esc(s.name) + '</td><td class="text-nowrap">' + esc(s.type) + '</td>' +
                    '<td class="text-center">' + s.boy + '</td><td class="text-center">' + s.girl + '</td>' +
                    '<td class="text-center fw-semibold">' + s.total + '</td><td>' + titles(s) + '</td></tr>';
            }).join('');
            out.innerHTML = '<div class="d-md-none">' + mob + '</div>' +
                '<div class="d-none d-md-block card"><div class="table-responsive"><table class="table table-striped table-hover mb-0 align-middle">' +
                '<thead class="table-light"><tr><th>#</th><th class="text-nowrap">鄉鎮市</th><th>學校名稱</th><th class="text-nowrap">學校類別</th>' +
                '<th class="text-center">男</th><th class="text-center">女</th><th class="text-center">合計</th><th>職務數統計</th></tr></thead>' +
                '<tbody>' + trs + '</tbody></table></div></div>';
        }
        update();
    }

    // ---------- 分頁：學生數統計、班級數統計（共用寬表格） ----------
    // mode 'students'：欄為年級（男、女、計），合計 = 總學生數
    // mode 'classes'：欄為班別（班數、男、女、計），合計 = 班級數
    function wideTab(data, mode) {
        var isStudents = mode === 'students';
        var opts = { district: true, search: true, county: true, exportKey: mode };
        var st = filterBar(opts, update);
        var sortDir = null;
        var out = el('<div></div>');
        body.replaceChildren(st.bar, el('<div>' + note(data.updated_at) + '</div>'), out);
        var head = isStudents ? 'table-primary' : 'table-success';
        var accent = isStudents ? 'text-primary' : 'text-success';
        var unit = isStudents ? '人' : '班';

        function columns(rows) {
            if (isStudents) {
                return (st.type === '國民小學' ? ELEMENTARY : st.type === '國民中學' ? JUNIOR : ELEMENTARY.concat(JUNIOR)).concat([SPECIAL_ED]);
            }
            var seen = {};
            rows.forEach(function (s) { Object.keys(s.details || {}).forEach(function (k) { seen[k] = true; }); });
            return sortKinds(Object.keys(seen));
        }
        var fields = isStudents ? ['b', 'g', 't'] : ['c', 'b', 'g', 't'];
        var fieldNames = isStudents ? ['男', '女', '計'] : ['班數', '男', '女', '計'];
        function rowTotal(s, cols) { return isStudents ? (s.total || 0) : classTotal(s, cols); }

        function update() {
            var rows = applyFilter(data.rows, st, opts);
            if (st.exportBtn) st.exportBtn.classList.toggle('disabled', !rows.length);
            if (!rows.length) { out.innerHTML = EMPTY; return; }
            var cols = columns(rows);
            var sorted = rows;
            if (sortDir) {
                sorted = rows.slice().sort(function (a, b) {
                    var d = rowTotal(a, cols) - rowTotal(b, cols);
                    return sortDir === 'asc' ? d : -d;
                });
            }
            var tot = {}, grand = 0;
            cols.forEach(function (k) { tot[k] = { c: 0, b: 0, g: 0, t: 0 }; });
            rows.forEach(function (s) {
                cols.forEach(function (k) {
                    var d = detail(s, k);
                    if (d) fields.forEach(function (f) { tot[k][f] += d[f] || 0; });
                });
                grand += rowTotal(s, cols);
            });

            // 手機
            var mob = '<button type="button" class="btn btn-outline-' + (isStudents ? 'primary' : 'success') +
                ' w-100 d-flex justify-content-between mb-2 ss-sort-btn"><span>依合計排序</span><span class="small">' + sortText(sortDir) + '</span></button>' +
                sorted.map(function (s, i) {
                    var inner = '<div class="' + (isStudents ? 'row row-cols-2 g-1' : 'd-grid gap-1') + '">' + cols.map(function (k) {
                        var d = detail(s, k);
                        if (!d || !d.t) return '';
                        return '<div' + (isStudents ? ' class="col"' : '') + '><div class="d-flex justify-content-between border rounded px-2 py-1">' +
                            '<span class="text-secondary">' + esc(k) + '</span>' +
                            (isStudents ? '' : '<span class="text-secondary">' + d.c + ' 班</span>') +
                            '<span><span class="text-primary">' + d.b + '</span>/<span class="text-danger">' + d.g + '</span> <span class="fw-semibold">=' + d.t + '</span></span>' +
                            '</div></div>';
                    }).join('') + '</div>';
                    return mobileCard(i, s.name, '合計 ' + rowTotal(s, cols) + ' ' + unit, inner);
                }).join('') +
                '<div class="card bg-body-secondary border-2"><div class="card-body py-2 d-flex justify-content-between small">' +
                '<span class="fw-semibold">總計</span><span class="fw-semibold ' + accent + '">' + grand + ' ' + unit + '</span></div></div>';

            // 電腦
            var thead = '<tr><th rowspan="2" class="br sc1">#</th><th rowspan="2" class="br sc2">學校代碼</th><th rowspan="2" class="br sc3">學校名稱</th>' +
                cols.map(function (k) { return '<th colspan="' + fields.length + '" class="br ' + head + '">' + esc(k) + '</th>'; }).join('') +
                '<th rowspan="2" class="br ss-sort ' + head + '">合計 <span class="small opacity-75">' + sortMark(sortDir) + '</span></th></tr>' +
                '<tr>' + cols.map(function () {
                    return fieldNames.map(function (n, j) {
                        return '<th class="' + head + (j === fieldNames.length - 1 ? ' br' : '') + '">' + n + '</th>';
                    }).join('');
                }).join('') + '</tr>';
            var tbody = sorted.map(function (s, i) {
                return '<tr><td class="br sc1 text-secondary">' + (i + 1) + '</td><td class="br sc2">' + esc(s.code) + '</td>' +
                    '<td class="br sc3 ss-name">' + esc(s.name) + '</td>' +
                    cols.map(function (k) {
                        var d = detail(s, k) || {};
                        return fields.map(function (f, j) {
                            return '<td' + (j === fields.length - 1 ? ' class="br"' : '') + '>' + blank(d[f]) + '</td>';
                        }).join('');
                    }).join('') +
                    '<td class="br fw-semibold ' + accent + '">' + blank(rowTotal(s, cols)) + '</td></tr>';
            }).join('');
            var tfoot = '<tr><td colspan="3" class="br sc1">總計</td>' + cols.map(function (k) {
                return fields.map(function (f, j) {
                    return '<td' + (j === fields.length - 1 ? ' class="br"' : '') + '>' + blank(tot[k][f]) + '</td>';
                }).join('');
            }).join('') + '<td class="br ' + accent + '">' + grand + '</td></tr>';

            var wrap = wideTable(thead, tbody, tfoot);
            out.replaceChildren(el('<div class="d-md-none">' + mob + '</div>'), el('<div class="d-none d-md-block"></div>'));
            out.lastChild.appendChild(wrap);
            fixSticky(wrap);

            var toggle = function () { sortDir = nextSort(sortDir); update(); };
            out.querySelector('.ss-sort-btn').addEventListener('click', toggle);
            out.querySelector('th.ss-sort').addEventListener('click', toggle);
        }
        update();
        return function () { var w = out.querySelector('.d-md-block > .position-relative'); if (w) fixSticky(w); };
    }
    function studentsTab(data) { return wideTab(data, 'students'); }
    function classesTab(data) { return wideTab(data, 'classes'); }

    // ---------- 分頁：統計（班級規模、鄉鎮市統計） ----------
    function sizeTab(data) {
        var view = 'size';
        var bucket = 'small';
        var opts = { district: true, county: true };
        var st = filterBar(opts, update);
        var modes = el('<div class="btn-group" role="group">' +
            '<button type="button" class="btn btn-primary" data-view="size">班級規模</button>' +
            '<button type="button" class="btn btn-outline-primary" data-view="district">鄉鎮市統計</button></div>');
        st.bar.insertBefore(modes, st.bar.firstChild);
        modes.querySelectorAll('button').forEach(function (b) {
            b.addEventListener('click', function () {
                view = b.dataset.view;
                if (view === 'district') {
                    shared.district = 'all';
                    st.districtSelect.value = 'all';
                }
                modes.querySelectorAll('button').forEach(function (x) {
                    x.className = 'btn ' + (x.dataset.view === view ? 'btn-primary' : 'btn-outline-primary');
                });
                st.districtSelect.classList.toggle('d-none', view === 'district');
                update();
            });
        });
        var out = el('<div></div>');
        body.replaceChildren(st.bar, el('<div>' + note(data.updated_at) + '</div>'), out);

        var CARDS = [
            { key: 'small', label: '≤' + SMALL + ' 班', cls: 'warning' },
            { key: 'medium', label: '>' + SMALL + ' ≤' + LARGE + ' 班', cls: 'success' },
            { key: 'large', label: '>' + LARGE + ' 班', cls: 'primary' },
        ];
        function bucketOf(n) { return n <= SMALL ? 'small' : n > LARGE ? 'large' : 'medium'; }

        function update() {
            var rows = applyFilter(data.rows, st, opts);
            if (!rows.length) { out.innerHTML = EMPTY; return; }
            var ann = rows.map(function (s) { var t = classTotal(s); return { s: s, total: t, bucket: bucketOf(t) }; });

            if (view === 'district') {
                var map = {};
                var list = [];
                ann.forEach(function (a) {
                    var d = a.s.district || '未知';
                    if (!map[d]) { map[d] = { district: d, schools: 0, classes: 0, small: 0, medium: 0, large: 0 }; list.push(map[d]); }
                    map[d].schools++;
                    map[d].classes += a.total;
                    map[d][a.bucket]++;
                });
                list.sort(function (a, b) { return b.classes - a.classes; });
                out.innerHTML = '<div class="card"><div class="card-header d-flex justify-content-between"><span>鄉鎮市班級統計</span>' +
                    '<span class="text-secondary">共 ' + list.length + ' 鄉鎮市</span></div>' +
                    '<div class="d-md-none list-group list-group-flush">' + list.map(function (d) {
                        return '<div class="list-group-item"><div class="d-flex justify-content-between"><span class="fw-medium">' + esc(d.district) + '</span>' +
                            '<span class="fw-bold">' + d.classes + ' 班</span></div><div class="d-flex gap-3 small text-secondary">' +
                            '<span>' + d.schools + ' 所</span><span class="text-warning-emphasis">≤' + SMALL + '班:' + d.small + '</span>' +
                            '<span class="text-success">' + (SMALL + 1) + '~' + LARGE + '班:' + d.medium + '</span>' +
                            '<span class="text-primary">＞' + LARGE + '班:' + d.large + '</span></div></div>';
                    }).join('') + '</div>' +
                    '<div class="d-none d-md-block table-responsive"><table class="table table-striped table-hover mb-0 text-center">' +
                    '<thead class="table-light"><tr><th>#</th><th class="text-start">鄉鎮市</th><th>學校數</th><th>班級總數</th>' +
                    '<th>≤' + SMALL + '班</th><th>' + (SMALL + 1) + '~' + LARGE + '班</th><th>＞' + LARGE + '班</th></tr></thead><tbody>' +
                    list.map(function (d, i) {
                        return '<tr><td class="text-secondary">' + (i + 1) + '</td><td class="text-start fw-medium">' + esc(d.district) + '</td>' +
                            '<td>' + d.schools + '</td><td class="fw-semibold">' + d.classes + '</td><td class="text-warning-emphasis">' + d.small + '</td>' +
                            '<td class="text-success">' + d.medium + '</td><td class="text-primary">' + d.large + '</td></tr>';
                    }).join('') + '</tbody></table></div></div>';
                return;
            }

            var counts = { small: 0, medium: 0, large: 0 };
            ann.forEach(function (a) { counts[a.bucket]++; });
            var list2 = ann.filter(function (a) { return a.bucket === bucket; }).sort(function (a, b) {
                return a.total - b.total || a.s.name.localeCompare(b.s.name, 'zh-TW');
            });
            var active = CARDS.filter(function (c) { return c.key === bucket; })[0];
            out.innerHTML = '<div class="row g-2 g-sm-3 mb-3">' + CARDS.map(function (c) {
                return '<div class="col-4"><button type="button" data-bucket="' + c.key + '" class="ss-bucket card w-100 h-100 bg-' + c.cls +
                    '-subtle border-' + c.cls + '-subtle text-' + c.cls + '-emphasis' + (c.key === bucket ? ' active' : '') + '">' +
                    '<div class="card-body py-3"><div class="fw-medium">' + c.label + '</div>' +
                    '<div class="fs-2 fw-bold">' + counts[c.key] + '<span class="fs-6 fw-normal opacity-75 ms-1">所</span></div></div></button></div>';
            }).join('') + '</div>' +
                '<div class="card"><div class="card-header d-flex justify-content-between"><span>' + active.label + '</span>' +
                '<span class="text-secondary">共 ' + list2.length + ' 所</span></div>' +
                (list2.length === 0 ? '<p class="text-secondary py-4 text-center mb-0">此分類沒有學校。</p>' :
                    '<div class="d-md-none list-group list-group-flush">' + list2.map(function (a, i) {
                        return '<div class="list-group-item d-flex align-items-center gap-3"><span class="text-secondary">' + (i + 1) + '</span>' +
                            '<div class="flex-grow-1 text-truncate"><div class="text-truncate">' + esc(a.s.name) + '</div>' +
                            '<div class="small text-secondary">' + esc(a.s.district) + esc(typeShort(a.s.type)) + '</div></div>' +
                            '<div class="fw-semibold">' + a.total + ' 班</div></div>';
                    }).join('') + '</div>' +
                    '<div class="d-none d-md-block table-responsive"><table class="table table-striped table-hover mb-0">' +
                    '<thead class="table-light"><tr><th class="text-center">#</th><th>學校名稱</th><th class="text-center">鄉鎮市</th>' +
                    '<th class="text-center">類別</th><th class="text-center">班級總數</th></tr></thead><tbody>' +
                    list2.map(function (a, i) {
                        return '<tr><td class="text-center text-secondary">' + (i + 1) + '</td><td class="text-nowrap">' + esc(a.s.name) + '</td>' +
                            '<td class="text-center">' + esc(a.s.district) + '</td><td class="text-center">' + esc(typeShort(a.s.type)) + '</td>' +
                            '<td class="text-center fw-semibold">' + a.total + '</td></tr>';
                    }).join('') + '</tbody></table></div>') + '</div>';
            out.querySelectorAll('[data-bucket]').forEach(function (b) {
                b.addEventListener('click', function () { bucket = b.dataset.bucket; update(); });
            });
        }
        update();
    }

    // ---------- 分頁切換（網址 #hash，同 Go 版） ----------
    var onResize = null;
    function indexOfHash() {
        var h = window.location.hash.replace('#', '');
        for (var i = 0; i < TABS.length; i++) if (TABS[i].hash === h) return i;
        return 0;
    }
    function renderTabs() {
        tabsEl.innerHTML = TABS.map(function (t, i) {
            return '<li class="nav-item"><a class="nav-link' + (i === current ? ' active' : '') + '" href="#' + t.hash + '">' +
                '<span class="d-md-none fw-bold">' + t.short + '</span><span class="d-none d-md-inline">' + t.full + '</span></a></li>';
        }).join('');
    }
    function show() {
        var i = indexOfHash();
        if (i === current) return;
        current = i;
        onResize = null;
        renderTabs();
        body.innerHTML = '<p class="text-secondary py-4 text-center">載入中…</p>';
        var tab = TABS[i];
        load(tab.data).then(function (data) {
            if (current !== i) return; // 已換到別的分頁
            if (!data.rows || !data.rows.length) {
                body.innerHTML = '<p class="text-secondary py-4 text-center">目前沒有資料。</p>';
                return;
            }
            onResize = tab.render(data) || null;
        }).catch(function () {
            if (current !== i) return;
            body.innerHTML = '<p class="text-danger py-4 text-center">資料載入失敗，請重新整理頁面。</p>';
        });
    }
    window.addEventListener('hashchange', show);
    window.addEventListener('resize', function () { if (onResize) onResize(); });
    show();
})();
