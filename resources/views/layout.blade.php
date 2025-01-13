<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>ㅔ?</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Custom fonts for this template-->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/xeicon@2.3.3/xeicon.min.css">
    <script
        src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/collect.js/4.18.3/collect.min.js" integrity="sha512-LkKpealLJ+RNIuYaXSC+l/0Zf5KjYCpMaUrON9WUC+LG316w3UEImyaUpWMWfqNGC4vLOkxDWEVKQE+Wp0shKg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>

    <script type="text/javascript">
        /**
         * 템플릿 문자열에서 [[...]] 안의 키를 실제 값으로 치환하는 함수
         * @param {string} template 템플릿 문자열
         * @param {object} data 치환할 데이터 객체
         * @returns {string} 치환된 문자열
         */
        function replaceTemplate(template, data) {
            return template.replace(/\[\[([^\]]+)\]\]/g, (match, key) => {
                // 키에 해당하는 값 반환, 없으면 빈 문자열
                return data[key] || '';
            });
        }
        function showModal(id) {
            const Modal = new bootstrap.Modal(document.getElementById(id));
            Modal.show();
        }

        // DOM이 준비된 이후에 실행되도록 보장
        document.addEventListener('DOMContentLoaded', () => {
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            if (csrfMeta) {
                const token = csrfMeta.getAttribute('content');
                if (token) {
                    axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
                } else {
                    console.error('CSRF token content is empty.');
                }
            } else {
                console.error('CSRF meta tag not found in the document.');
            }
        });


        function notify(title, message) {
            $("#liveToast .toast-header strong").text(title);
            $("#liveToast .toast-body").text(message);
            $("#liveToast").toast("show");
        }

    </script>
</head>
<body >
<div class="wrap">
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-light bg-light" @auth loginid="{!! Auth::id() !!}" @endauth>
            @include("components.nav")
        </nav>
        <div class="container-fluid mt-3">
            <div class="row mb-3">
                <div class="col-4">
                    @include("components.news")

                </div>
                <div class="col-2">
                    @include("components.property")
                </div>
                <div class="col-6">
                    @include("components.trade-chart")
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12">
                    @include("components.trade")
                </div>
            </div>
        </div>
    </div>
</div>
@include("components.buyForm")
@include("components.sellForm")
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto"></strong>
                <small></small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body"></div>
        </div>
    </div>
</body>
<script type="text/javascript">

    // 전역 변수 선언
    let globalCache = null;

    // 캐시 동기화 함수
    const syncCache = async () => {
        try {
            const response = await axios.get('/sync');
            if (response.data.status === 'success') {
                const newCache = response.data.data;
                // 캐시 값이 변경되었는지 확인
                if (JSON.stringify(globalCache) !== JSON.stringify(newCache)) {
                    globalCache = newCache; // 변경된 값으로 갱신
                    // 변경에 따른 추가 작업 실행
                    handleCacheUpdate(globalCache);
                } else {

                }
            }
        } catch (error) {
            console.error('Error fetching cache:', error);
        }
    };

    // 캐시 변경 시 추가 작업을 처리하는 함수
    const handleCacheUpdate = (updatedCache) => {
        // 필요한 추가 로직을 여기에 작성
        notify("NEWS", "새로운 뉴스가 있습니다.")
    };

    // 페이지 로드 시 초기화 및 주기적 동기화
    document.addEventListener('DOMContentLoaded', () => {
        syncCache(); // 초기 캐시 동기화
        // 5초마다 캐시 동기화
        const second = 5 * 1000;
        setInterval(syncCache, second);
    });


</script>

</html>
