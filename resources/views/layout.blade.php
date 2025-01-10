<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>ㅔ?</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Custom fonts for this template-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/xeicon@2.3.3/xeicon.min.css">
    <script
        src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/collect.js/4.18.3/collect.min.js" integrity="sha512-LkKpealLJ+RNIuYaXSC+l/0Zf5KjYCpMaUrON9WUC+LG316w3UEImyaUpWMWfqNGC4vLOkxDWEVKQE+Wp0shKg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            console.log(id);
            console.log(document.getElementById(id));
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
    </script>
</head>
<body >
<div class="wrap">
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-light bg-light" @auth loginid="{!! Auth::id() !!}" @endauth>
            @include("components.nav")
        </nav>
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-6">
                    @include("components.news")

                </div>
                <div class="col-6">
                    @include("components.chart")
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
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>
