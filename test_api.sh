#!/bin/bash
BASE="http://localhost:8000/api/v1"
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m'

pass=0
fail=0

check() {
    local label=$1
    local expected_code=$2
    local actual_code=$3
    local body=$4
    
    if [ "$actual_code" == "$expected_code" ]; then
        echo -e "${GREEN}[PASS]${NC} $label (HTTP $actual_code)"
        ((pass++))
    else
        echo -e "${RED}[FAIL]${NC} $label — expected $expected_code, got $actual_code"
        echo "       Response: $(echo $body | head -c 200)"
        ((fail++))
    fi
}

echo ""
echo "========================================"
echo " HAYO CHICKEN API AUDIT"
echo "========================================"

# ── BLOK 1: AUTH ──────────────────────────────────────────────
echo ""
echo "--- BLOK 1: AUTENTIKASI ---"

# Register
RESP=$(curl -s -w "\n%{http_code}" -X POST "$BASE/auth/register" \
  -H "Content-Type: application/json" \
  -d '{"name":"Tester Audit","email":"audit_'$(date +%s)'@test.com","phone":"+6281234567890","password":"password123","password_confirmation":"password123"}')
BODY=$(echo "$RESP" | head -n -1)
CODE=$(echo "$RESP" | tail -n 1)
check "Register akun baru" "201" "$CODE" "$BODY"
ACCESS_TOKEN=$(echo $BODY | grep -o '"access_token":"[^"]*"' | cut -d'"' -f4)

# Login
EMAIL="audit_seller_$(date +%s)@test.com"
RESP=$(curl -s -w "\n%{http_code}" -X POST "$BASE/auth/login" \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@hayochicken.com","password":"password123"}')
BODY=$(echo "$RESP" | head -n -1)
CODE=$(echo "$RESP" | tail -n 1)
check "Login seller (dari seeder)" "200" "$CODE" "$BODY"
SELLER_TOKEN=$(echo $BODY | grep -o '"access_token":"[^"]*"' | cut -d'"' -f4)
REFRESH_TOKEN=$(echo $BODY | grep -o '"refresh_token":"[^"]*"' | cut -d'"' -f4)

# Login customer
RESP=$(curl -s -w "\n%{http_code}" -X POST "$BASE/auth/login" \
  -H "Content-Type: application/json" \
  -d '{"email":"budi@gmail.com","password":"password123"}')
BODY=$(echo "$RESP" | head -n -1)
CODE=$(echo "$RESP" | tail -n 1)
check "Login customer (dari seeder)" "200" "$CODE" "$BODY"
CUSTOMER_TOKEN=$(echo $BODY | grep -o '"access_token":"[^"]*"' | cut -d'"' -f4)
CUSTOMER_REFRESH=$(echo $BODY | grep -o '"refresh_token":"[^"]*"' | cut -d'"' -f4)

# Refresh token
RESP=$(curl -s -w "\n%{http_code}" -X POST "$BASE/auth/refresh" \
  -H "Content-Type: application/json" \
  -d "{\"refresh_token\":\"$CUSTOMER_REFRESH\"}")
BODY=$(echo "$RESP" | head -n -1)
CODE=$(echo "$RESP" | tail -n 1)
check "Refresh token rotation" "200" "$CODE" "$BODY"
NEW_ACCESS=$(echo $BODY | grep -o '"access_token":"[^"]*"' | cut -d'"' -f4)
[ -n "$NEW_ACCESS" ] && CUSTOMER_TOKEN=$NEW_ACCESS

# Login dengan password salah
RESP=$(curl -s -w "\n%{http_code}" -X POST "$BASE/auth/login" \
  -H "Content-Type: application/json" \
  -d '{"email":"budi@gmail.com","password":"salah123"}')
BODY=$(echo "$RESP" | head -n -1)
CODE=$(echo "$RESP" | tail -n 1)
check "Login password salah → 401" "401" "$CODE" "$BODY"

# Phone format salah
RESP=$(curl -s -w "\n%{http_code}" -X POST "$BASE/auth/register" \
  -H "Content-Type: application/json" \
  -d '{"name":"Test","email":"formattest@test.com","phone":"08123456789","password":"password123","password_confirmation":"password123"}')
BODY=$(echo "$RESP" | head -n -1)
CODE=$(echo "$RESP" | tail -n 1)
check "Register phone format salah (bukan +62) → 422" "422" "$CODE" "$BODY"

# ── BLOK 2: PRODUK & KATEGORI ─────────────────────────────────
echo ""
echo "--- BLOK 2: PRODUK & KATEGORI ---"

RESP=$(curl -s -w "\n%{http_code}" "$BASE/categories")
BODY=$(echo "$RESP" | head -n -1)
CODE=$(echo "$RESP" | tail -n 1)
check "GET categories" "200" "$CODE" "$BODY"
HAS_SORT=$(echo $BODY | grep -c "sort_order")
[ "$HAS_SORT" -gt 0 ] && echo -e "       ${GREEN}↳ sort_order ada di response${NC}" || echo -e "       ${RED}↳ sort_order TIDAK ada${NC}"

RESP=$(curl -s -w "\n%{http_code}" "$BASE/products")
BODY=$(echo "$RESP" | head -n -1)
CODE=$(echo "$RESP" | tail -n 1)
check "GET products (paginated)" "200" "$CODE" "$BODY"
HAS_PAGINATION=$(echo $BODY | grep -c '"current_page"')
[ "$HAS_PAGINATION" -gt 0 ] && echo -e "       ${GREEN}↳ pagination ada${NC}" || echo -e "       ${RED}↳ pagination TIDAK ada${NC}"
PRODUCT_ID=$(echo $BODY | grep -o '"id":[0-9]*' | head -1 | cut -d':' -f2)

RESP=$(curl -s -w "\n%{http_code}" "$BASE/products/$PRODUCT_ID")
BODY=$(echo "$RESP" | head -n -1)
CODE=$(echo "$RESP" | tail -n 1)
check "GET product detail (id=$PRODUCT_ID)" "200" "$CODE" "$BODY"
HAS_EXTRAS=$(echo $BODY | grep -c "product_extras")
[ "$HAS_EXTRAS" -gt 0 ] && echo -e "       ${GREEN}↳ product_extras ada di response${NC}" || echo -e "       ${YELLOW}↳ product_extras kosong/tidak ada${NC}"

# Akses endpoint terproteksi tanpa token → 401
RESP=$(curl -s -w "\n%{http_code}" "$BASE/cart")
BODY=$(echo "$RESP" | head -n -1)
CODE=$(echo "$RESP" | tail -n 1)
check "Akses cart tanpa token → 401" "401" "$CODE" "$BODY"

# ── BLOK 3: KERANJANG ─────────────────────────────────────────
echo ""
echo "--- BLOK 3: KERANJANG ---"

# Tambah item ke cart
RESP=$(curl -s -w "\n%{http_code}" -X POST "$BASE/cart" \
  -H "Authorization: Bearer $CUSTOMER_TOKEN" \
  -H "Content-Type: application/json" \
  -d "{\"product_id\":$PRODUCT_ID,\"quantity\":2,\"selected_extras_snapshot\":[]}")
BODY=$(echo "$RESP" | head -n -1)
CODE=$(echo "$RESP" | tail -n 1)
check "POST cart (tambah item)" "200" "$CODE" "$BODY"
CART_ITEM_ID=$(echo $BODY | grep -o '"id":[0-9]*' | head -1 | cut -d':' -f2)

# Upsert: tambah produk sama dengan extras sama → harus increment quantity
RESP=$(curl -s -w "\n%{http_code}" -X POST "$BASE/cart" \
  -H "Authorization: Bearer $CUSTOMER_TOKEN" \
  -H "Content-Type: application/json" \
  -d "{\"product_id\":$PRODUCT_ID,\"quantity\":1,\"selected_extras_snapshot\":[]}")
BODY=$(echo "$RESP" | head -n -1)
CODE=$(echo "$RESP" | tail -n 1)
check "POST cart upsert (extras sama → increment qty)" "200" "$CODE" "$BODY"
QTY=$(echo $BODY | grep -o '"quantity":[0-9]*' | head -1 | cut -d':' -f2)
[ "$QTY" == "3" ] && echo -e "       ${GREEN}↳ quantity jadi 3 (2+1) ✓${NC}" || echo -e "       ${RED}↳ quantity = $QTY, harusnya 3 (upsert gagal?)${NC}"

# Toggle check
RESP=$(curl -s -w "\n%{http_code}" -X PATCH "$BASE/cart/$CART_ITEM_ID/toggle-check" \
  -H "Authorization: Bearer $CUSTOMER_TOKEN")
BODY=$(echo "$RESP" | head -n -1)
CODE=$(echo "$RESP" | tail -n 1)
check "PATCH cart toggle-check" "200" "$CODE" "$BODY"

# Toggle balik supaya is_checked = true untuk checkout
curl -s -X PATCH "$BASE/cart/$CART_ITEM_ID/toggle-check" \
  -H "Authorization: Bearer $CUSTOMER_TOKEN" > /dev/null

# GET cart
RESP=$(curl -s -w "\n%{http_code}" "$BASE/cart" \
  -H "Authorization: Bearer $CUSTOMER_TOKEN")
BODY=$(echo "$RESP" | head -n -1)
CODE=$(echo "$RESP" | tail -n 1)
check "GET cart" "200" "$CODE" "$BODY"

# ── BLOK 4: CHECKOUT ──────────────────────────────────────────
echo ""
echo "--- BLOK 4: CHECKOUT ---"

# Checkout COD
RESP=$(curl -s -w "\n%{http_code}" -X POST "$BASE/orders" \
  -H "Authorization: Bearer $CUSTOMER_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"delivery_address":"Jl. Test No.1, Purwokerto","payment_method":"COD"}')
BODY=$(echo "$RESP" | head -n -1)
CODE=$(echo "$RESP" | tail -n 1)
check "POST orders checkout COD" "200" "$CODE" "$BODY"
ORDER_NUM=$(echo $BODY | grep -o '"order_number":"[^"]*"' | cut -d'"' -f4)
ORDER_ID=$(echo $BODY | grep -o '"order_id":[0-9]*' | cut -d':' -f2)
HC_FORMAT=$(echo $ORDER_NUM | grep -c "^HC-")
[ "$HC_FORMAT" -gt 0 ] && echo -e "       ${GREEN}↳ format order_number: $ORDER_NUM ✓${NC}" || echo -e "       ${RED}↳ format salah: $ORDER_NUM${NC}"

# Cek cart sudah kosong setelah checkout
RESP=$(curl -s "$BASE/cart" -H "Authorization: Bearer $CUSTOMER_TOKEN")
CART_COUNT=$(echo $RESP | grep -o '"id"' | wc -l)
[ "$CART_COUNT" -eq 0 ] && echo -e "       ${GREEN}↳ cart bersih setelah checkout ✓${NC}" || echo -e "       ${RED}↳ cart masih ada $CART_COUNT item setelah checkout${NC}"

# Checkout tanpa item (cart kosong)
RESP=$(curl -s -w "\n%{http_code}" -X POST "$BASE/orders" \
  -H "Authorization: Bearer $CUSTOMER_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"delivery_address":"Jl. Test","payment_method":"COD"}')
BODY=$(echo "$RESP" | head -n -1)
CODE=$(echo "$RESP" | tail -n 1)
check "Checkout cart kosong → 422" "422" "$CODE" "$BODY"

# Checkout QRIS tanpa file → harus 422
RESP=$(curl -s -w "\n%{http_code}" -X POST "$BASE/orders" \
  -H "Authorization: Bearer $CUSTOMER_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"delivery_address":"Jl. Test","payment_method":"QRIS_MANUAL"}')
BODY=$(echo "$RESP" | head -n -1)
CODE=$(echo "$RESP" | tail -n 1)
check "Checkout QRIS tanpa receipt → 422" "422" "$CODE" "$BODY"

# ── BLOK 5: STATE MACHINE ─────────────────────────────────────
echo ""
echo "--- BLOK 5: STATE MACHINE (SELLER) ---"

# Isi cart lagi untuk buat order baru
curl -s -X POST "$BASE/cart" \
  -H "Authorization: Bearer $CUSTOMER_TOKEN" \
  -H "Content-Type: application/json" \
  -d "{\"product_id\":$PRODUCT_ID,\"quantity\":1,\"selected_extras_snapshot\":[]}" > /dev/null

RESP=$(curl -s -w "\n%{http_code}" -X POST "$BASE/orders" \
  -H "Authorization: Bearer $CUSTOMER_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"delivery_address":"Jl. Test No.2","payment_method":"COD"}')
BODY=$(echo "$RESP" | head -n -1)
ORDER_ID2=$(echo $BODY | grep -o '"order_id":[0-9]*' | cut -d':' -f2)

# Update status NEW → PROCESSING (valid)
RESP=$(curl -s -w "\n%{http_code}" -X PATCH "$BASE/seller/orders/$ORDER_ID2/status" \
  -H "Authorization: Bearer $SELLER_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"status":"PROCESSING","note":"Pesanan diterima"}')
BODY=$(echo "$RESP" | head -n -1)
CODE=$(echo "$RESP" | tail -n 1)
check "PATCH status NEW→PROCESSING (valid)" "200" "$CODE" "$BODY"

# Transisi ilegal PROCESSING → DONE (skip DELIVERING)
RESP=$(curl -s -w "\n%{http_code}" -X PATCH "$BASE/seller/orders/$ORDER_ID2/status" \
  -H "Authorization: Bearer $SELLER_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"status":"DONE"}')
BODY=$(echo "$RESP" | head -n -1)
CODE=$(echo "$RESP" | tail -n 1)
check "PATCH status PROCESSING→DONE langsung (ilegal) → 422" "422" "$CODE" "$BODY"

# REJECTED tanpa note → 422
RESP=$(curl -s -w "\n%{http_code}" -X PATCH "$BASE/seller/orders/$ORDER_ID2/status" \
  -H "Authorization: Bearer $SELLER_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"status":"REJECTED"}')
BODY=$(echo "$RESP" | head -n -1)
CODE=$(echo "$RESP" | tail -n 1)
check "PATCH status REJECTED tanpa note → 422" "422" "$CODE" "$BODY"

# REJECTED dengan note → OK
RESP=$(curl -s -w "\n%{http_code}" -X PATCH "$BASE/seller/orders/$ORDER_ID2/status" \
  -H "Authorization: Bearer $SELLER_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"status":"REJECTED","note":"Stok habis mendadak"}')
BODY=$(echo "$RESP" | head -n -1)
CODE=$(echo "$RESP" | tail -n 1)
check "PATCH status REJECTED dengan note → 200" "200" "$CODE" "$BODY"

# Customer akses seller endpoint → 403
RESP=$(curl -s -w "\n%{http_code}" -X PATCH "$BASE/seller/orders/$ORDER_ID2/status" \
  -H "Authorization: Bearer $CUSTOMER_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"status":"PROCESSING"}')
BODY=$(echo "$RESP" | head -n -1)
CODE=$(echo "$RESP" | tail -n 1)
check "Customer akses seller endpoint → 403" "403" "$CODE" "$BODY"

# ── BLOK 6: ANALYTICS ─────────────────────────────────────────
echo ""
echo "--- BLOK 6: ANALYTICS ---"

RESP=$(curl -s -w "\n%{http_code}" "$BASE/seller/analytics/summary" \
  -H "Authorization: Bearer $SELLER_TOKEN")
BODY=$(echo "$RESP" | head -n -1)
CODE=$(echo "$RESP" | tail -n 1)
check "GET analytics/summary (seller)" "200" "$CODE" "$BODY"
HAS_FORECAST=$(echo $BODY | grep -c "forecasting")
HAS_TOP=$(echo $BODY | grep -c "top_products")
[ "$HAS_FORECAST" -gt 0 ] && echo -e "       ${GREEN}↳ forecasting ada ✓${NC}" || echo -e "       ${RED}↳ forecasting TIDAK ada${NC}"
[ "$HAS_TOP" -gt 0 ] && echo -e "       ${GREEN}↳ top_products ada ✓${NC}" || echo -e "       ${RED}↳ top_products TIDAK ada${NC}"

# Customer akses analytics → 403
RESP=$(curl -s -w "\n%{http_code}" "$BASE/seller/analytics/summary" \
  -H "Authorization: Bearer $CUSTOMER_TOKEN")
BODY=$(echo "$RESP" | head -n -1)
CODE=$(echo "$RESP" | tail -n 1)
check "Customer akses analytics → 403" "403" "$CODE" "$BODY"

# ── LOGOUT ────────────────────────────────────────────────────
echo ""
echo "--- LOGOUT ---"

RESP=$(curl -s -w "\n%{http_code}" -X POST "$BASE/auth/logout" \
  -H "Authorization: Bearer $CUSTOMER_TOKEN" \
  -H "Content-Type: application/json" \
  -d "{\"refresh_token\":\"$CUSTOMER_REFRESH\"}")
BODY=$(echo "$RESP" | head -n -1)
CODE=$(echo "$RESP" | tail -n 1)
check "POST auth/logout" "200" "$CODE" "$BODY"

# Pakai token lama setelah logout → harusnya 401
RESP=$(curl -s -w "\n%{http_code}" "$BASE/cart" \
  -H "Authorization: Bearer $CUSTOMER_TOKEN")
BODY=$(echo "$RESP" | head -n -1)
CODE=$(echo "$RESP" | tail -n 1)
check "Akses dengan token setelah logout → 401" "401" "$CODE" "$BODY"

# ── SUMMARY ───────────────────────────────────────────────────
echo ""
echo "========================================"
TOTAL=$((pass+fail))
echo " HASIL: $pass/$TOTAL PASS"
if [ $fail -eq 0 ]; then
    echo -e " ${GREEN}SEMUA HIJAU — SIAP KE FLUTTER ✓${NC}"
else
    echo -e " ${RED}$fail TEST GAGAL — PERBAIKI DULU${NC}"
fi
echo "========================================"
echo ""