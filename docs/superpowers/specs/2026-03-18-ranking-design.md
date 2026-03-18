# 랭킹순위보기 기능 설계

## 개요

유저별 총 자산(현금 + 보유주식 평가액)을 기준으로 순위를 매겨 보여주는 기능.
기존 레이아웃 내 컴포넌트로 추가하며, 별도 페이지 없이 메인 화면에서 확인 가능.

## 요구사항

- 랭킹 기준: 총 자산 (현금 + 보유주식 현재 평가액)
- 표시 정보: 순위, 닉네임, 총 자산
- 공개: 비로그인 유저도 조회 가능
- 상위 10명 표시

## 백엔드

### 엔드포인트

- `GET /ranking` — 공개 API (인증 불필요)
- 라우트 위치: `routes/web.php` 최상단 (미들웨어 그룹 밖)

### 컨트롤러

`TradeController`에 `getRanking()` 메서드 추가.

### 집계 로직

유저별 총 자산을 다음과 같이 계산:

1. **현금**: `stock_trades`에서 유저별 가장 최근 레코드(`MAX(id)`)의 `now_amount`
2. **주식 평가액**: `inventories`에서 유저별 보유 수량(`amount`) × 해당 종목 최신 시세(`stock_historys`에서 종목별 최신 `now_amount`) → 합산
3. **총 자산** = 현금 + 주식 평가액, DESC 정렬
4. `users` 테이블 JOIN으로 `nick_name` 가져오기 (`nick_name`이 null이면 `name` fallback)
5. 상위 10명만 반환

### 응답 형식

```json
{
  "code": "0000",
  "datas": [
    { "rank": 1, "nick_name": "유저A", "total_asset": 15000000 },
    { "rank": 2, "nick_name": "유저B", "total_asset": 12000000 }
  ]
}
```

## 프론트엔드

### 컴포넌트

`resources/views/components/ranking.blade.php` 신규 생성.

### 배치

`layout.blade.php`에서 기존 `property.blade.php`와 같은 col-2 컬럼 안에 아래로 쌓이는 형태:

```
row1: [뉴스 col-4] [내재산 + 랭킹 col-2] [차트 col-6]
```

### UI 구성

- BS5 `card` 컴포넌트 (기존 property 카드와 동일 스타일)
- 페이지 로드 시 `axios.get("/ranking")`으로 데이터 로드
- `dpPrice()` 함수 재활용하여 금액 포맷
- 테이블 형태: 순위 | 닉네임 | 총 자산

## 수정 대상 파일

| 파일 | 작업 |
|------|------|
| `app/Http/Controllers/TradeController.php` | `getRanking()` 메서드 추가 |
| `routes/web.php` | `GET /ranking` 라우트 추가 |
| `resources/views/components/ranking.blade.php` | 신규 생성 |
| `resources/views/layout.blade.php` | 랭킹 컴포넌트 include 추가 |

## 향후 확장

집계 로직이 `getRanking()` 한 곳에 격리되어 있으므로, 유저 증가 시:
- **캐시 테이블**: 스케줄러에서 `getRanking()` 결과를 별도 테이블에 저장
- **Redis 캐시**: `Cache::remember()` 한 줄 래핑

어느 쪽이든 집계 로직 변경 없이 호출부만 수정하면 됨 (10~15분 수준).
