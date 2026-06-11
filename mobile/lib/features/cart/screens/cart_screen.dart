import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import 'package:google_fonts/google_fonts.dart';
import '../providers/cart_provider.dart';
import '../../../core/theme/app_theme.dart';

class CartScreen extends ConsumerWidget {
  const CartScreen({super.key});

  String _getProductAsset(String? name) {
    if (name == null) return "assets/images/ayam_geprek.png";
    String n = name.toLowerCase();
    if (n.contains("geprek")) return "assets/images/ayam_geprek.png";
    if (n.contains("crispy")) return "assets/images/fried_chicken.png";
    if (n.contains("jebew")) return "assets/images/mie_jebew.png";
    if (n.contains("lemon")) return "assets/images/lemon_tea.png";
    if (n.contains("rice bowl")) return "assets/images/rice_bowl.png";
    if (n.contains("combo")) return "assets/images/paket_combo.png";
    return "assets/images/fried_chicken.png";
  }

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final state = ref.watch(cartProvider);
    final notifier = ref.read(cartProvider.notifier);

    return Scaffold(
      backgroundColor: const Color(0xFFF9F4EB),
      body: Stack(
        children: [
          Column(
            children: [
              Container(
                padding: const EdgeInsets.fromLTRB(20, 60, 20, 24),
                decoration: const BoxDecoration(
                  color: AppColors.primary,
                  borderRadius: BorderRadius.only(bottomLeft: Radius.circular(35), bottomRight: Radius.circular(35)),
                ),
                child: Row(
                  children: [
                    InkWell(
                      onTap: () => context.go('/home'),
                      borderRadius: BorderRadius.circular(50),
                      child: Container(
                        padding: const EdgeInsets.all(8),
                        decoration: BoxDecoration(color: Colors.white.withOpacity(0.2), shape: BoxShape.circle),
                        child: const Icon(Icons.chevron_left, color: Colors.white),
                      ),
                    ),
                    const SizedBox(width: 16),
                    Text("Keranjang", style: GoogleFonts.inter(color: Colors.white, fontSize: 20, fontWeight: FontWeight.w900)),
                  ],
                ),
              ),

              Expanded(
                child: state.isLoading 
                  ? const Center(child: CircularProgressIndicator(color: AppColors.primary))
                  : state.items.isEmpty
                    ? _buildEmptyState(context)
                    : ListView(
                        padding: const EdgeInsets.fromLTRB(20, 24, 20, 220),
                        children: [
                          // SELECT ALL
                          Material(
                            color: Colors.white,
                            borderRadius: BorderRadius.circular(20),
                            child: InkWell(
                              onTap: () => notifier.toggleSelectAll(),
                              borderRadius: BorderRadius.circular(20),
                              child: Padding(
                                padding: const EdgeInsets.all(16),
                                child: Row(
                                  children: [
                                    Container(
                                      width: 24, height: 24,
                                      decoration: BoxDecoration(
                                        color: state.items.isNotEmpty && state.items.every((i) => i.isChecked) ? const Color(0xFF9B1A1A) : Colors.white,
                                        borderRadius: BorderRadius.circular(6),
                                        border: Border.all(color: const Color(0xFF9B1A1A), width: 2),
                                      ),
                                      child: state.items.isNotEmpty && state.items.every((i) => i.isChecked) 
                                        ? const Icon(Icons.check, color: Colors.white, size: 16) : null,
                                    ),
                                    const SizedBox(width: 14),
                                    Text("Pilih Semua", style: GoogleFonts.inter(fontWeight: FontWeight.w800, fontSize: 14)),
                                    const Spacer(),
                                    Text("${state.totalCount} item", style: GoogleFonts.inter(color: Colors.grey, fontSize: 12, fontWeight: FontWeight.w600)),
                                    const SizedBox(width: 10),
                                    GestureDetector(
                                      onTap: () => notifier.removeCheckedItems(),
                                      child: Text("Hapus", style: GoogleFonts.inter(color: Colors.red, fontSize: 12, fontWeight: FontWeight.w900)),
                                    ),
                                  ],
                                ),
                              ),
                            ),
                          ),
                          const SizedBox(height: 16),

                          // ITEMS
                          ...state.items.map((item) => _CartItemCard(
                            key: ValueKey(item.id),
                            item: item, 
                            asset: _getProductAsset(item.product?.name)
                          )),
                          
                          const SizedBox(height: 16),
                          Container(
                            padding: const EdgeInsets.all(18),
                            decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(20)),
                            child: Column(
                              children: [
                                Row(
                                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                  children: [
                                    Text("Subtotal (${state.totalCount} item)", style: GoogleFonts.inter(color: Colors.grey, fontSize: 13, fontWeight: FontWeight.w600)),
                                    Text("Rp ${state.totalAmount}", style: GoogleFonts.inter(color: Colors.black54, fontSize: 13, fontWeight: FontWeight.w700)),
                                  ],
                                ),
                                const Padding(padding: EdgeInsets.symmetric(vertical: 10), child: Divider(color: Color(0xFFF5EFE6), thickness: 1.5, height: 1)),
                                Row(
                                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                  children: [
                                    Text("Total", style: GoogleFonts.inter(fontWeight: FontWeight.w900, fontSize: 15)),
                                    Text("Rp ${state.totalAmount}", style: GoogleFonts.inter(color: AppColors.primary, fontWeight: FontWeight.w900, fontSize: 18)),
                                  ],
                                ),
                              ],
                            ),
                          ),
                        ],
                      ),
              ),
            ],
          ),

          // BOTTOM BAR - Only show checkout button if cart is not empty
          Positioned(
            bottom: 40, left: 24, right: 24,
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 18, vertical: 14),
                  margin: const EdgeInsets.only(bottom: 16),
                  decoration: BoxDecoration(
                    color: AppColors.primary,
                    borderRadius: BorderRadius.circular(22),
                    boxShadow: [BoxShadow(color: AppColors.primary.withOpacity(0.3), blurRadius: 15, offset: const Offset(0, 8))],
                  ),
                  child: Row(
                    children: [
                      Container(padding: const EdgeInsets.all(8), decoration: BoxDecoration(color: Colors.white.withOpacity(0.2), borderRadius: BorderRadius.circular(10)), child: const Icon(Icons.timer_outlined, color: Colors.white, size: 22)),
                      const SizedBox(width: 14),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text("5 Pesanan Aktif", style: GoogleFonts.inter(color: Colors.white, fontWeight: FontWeight.w900, fontSize: 13)),
                            Text("Pantau Status Pesananmu", style: GoogleFonts.inter(color: Colors.white70, fontSize: 11, fontWeight: FontWeight.w600)),
                          ],
                        ),
                      ),
                      const Icon(Icons.chevron_right, color: Colors.white, size: 20),
                    ],
                  ),
                ),
                
                if (state.items.isNotEmpty)
                  SizedBox(
                    width: double.infinity, height: 60,
                    child: ElevatedButton(
                      onPressed: () => context.push('/checkout'),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: AppColors.primary,
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(50)),
                        elevation: 5,
                        shadowColor: AppColors.primary.withOpacity(0.4),
                      ),
                      child: Text("Checkout (${state.totalCount} item) - Rp${state.totalAmount}", style: GoogleFonts.inter(fontSize: 15, fontWeight: FontWeight.w900, color: Colors.white)),
                    ),
                  ),
              ],
            ),
          ),
          
          if (state.isUpdating)
            Positioned(top: 100, right: 20, child: Container(padding: const EdgeInsets.all(8), decoration: const BoxDecoration(color: Colors.white, shape: BoxShape.circle), child: const SizedBox(width: 15, height: 15, child: CircularProgressIndicator(strokeWidth: 2, color: AppColors.primary)))),
        ],
      ),
    );
  }

  Widget _buildEmptyState(BuildContext context) {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(Icons.shopping_bag_outlined, size: 120, color: AppColors.primary.withOpacity(0.3)),
          const SizedBox(height: 32),
          Text("Keranjang Kosong", style: GoogleFonts.inter(fontSize: 22, fontWeight: FontWeight.w900, color: const Color(0xFF1A1A1A))),
          const SizedBox(height: 12),
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 50),
            child: Text(
              "Kamu belum menambahkan menu apapun ke keranjang.", 
              textAlign: TextAlign.center,
              style: GoogleFonts.inter(color: Colors.grey, fontSize: 13, fontWeight: FontWeight.w500, height: 1.5),
            ),
          ),
          const SizedBox(height: 48),
          SizedBox(
            width: 220, height: 50,
            child: ElevatedButton(
              onPressed: () => context.go('/home'),
              style: ElevatedButton.styleFrom(
                backgroundColor: AppColors.primary,
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(50)),
                elevation: 10, shadowColor: AppColors.primary.withOpacity(0.4),
              ),
              child: Text("Mulai Belanja Sekarang", style: GoogleFonts.inter(color: Colors.white, fontWeight: FontWeight.w800, fontSize: 14)),
            ),
          ),
          const SizedBox(height: 80),
        ],
      ),
    );
  }
}

class _CartItemCard extends ConsumerWidget {
  final dynamic item;
  final String asset;
  const _CartItemCard({super.key, required this.item, required this.asset});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final notifier = ref.read(cartProvider.notifier);

    return Container(
      margin: const EdgeInsets.only(bottom: 16),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(24), boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.01), blurRadius: 10, offset: const Offset(0, 4))]),
      child: Row(
        children: [
          // CHECKBOX AREA (Large click area)
          InkWell(
            onTap: () => notifier.toggleCheck(item.id),
            borderRadius: BorderRadius.circular(8),
            child: Padding(
              padding: const EdgeInsets.all(8.0),
              child: Container(
                width: 24, height: 24,
                decoration: BoxDecoration(
                  color: item.isChecked ? const Color(0xFF9B1A1A) : Colors.white,
                  borderRadius: BorderRadius.circular(6),
                  border: Border.all(color: const Color(0xFF9B1A1A), width: 2),
                ),
                child: item.isChecked ? const Icon(Icons.check, color: Colors.white, size: 16) : null,
              ),
            ),
          ),
          const SizedBox(width: 6),
          Container(
            width: 70, height: 70,
            decoration: BoxDecoration(color: const Color(0xFFF5EFE6), borderRadius: BorderRadius.circular(16)),
            child: Padding(padding: const EdgeInsets.all(4.0), child: Image.asset(asset)),
          ),
          const SizedBox(width: 14),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(item.product?.name ?? "Produk", style: GoogleFonts.inter(fontWeight: FontWeight.w900, fontSize: 13, color: const Color(0xFF1A1A1A))),
                
                // EXTRAS & NOTE AREA (MANDATORY DISPLAY)
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    if (item.selectedExtras.isNotEmpty)
                      Padding(
                        padding: const EdgeInsets.only(top: 4, bottom: 2),
                        child: Text(
                          "Topping: ${item.selectedExtras.join(", ")}", 
                          style: GoogleFonts.inter(fontSize: 10, color: const Color(0xFFBBAA99), fontWeight: FontWeight.w800)
                        ),
                      ),

                    if (item.note != null && item.note!.isNotEmpty)
                      Container(
                        margin: const EdgeInsets.only(top: 4),
                        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                        decoration: BoxDecoration(
                          color: const Color(0xFFF1E9DA),
                          borderRadius: BorderRadius.circular(8),
                        ),
                        child: Text(
                          "Catatan: ${item.note!}", 
                          style: GoogleFonts.inter(fontSize: 10, fontStyle: FontStyle.italic, color: const Color(0xFF8B1A1A), fontWeight: FontWeight.w600),
                          maxLines: 2, 
                          overflow: TextOverflow.ellipsis,
                        ),
                      ),
                  ],
                ),

                const SizedBox(height: 4),
                // UNIT PRICE (Base + Extras)
                Text(
                  "Rp ${item.subtotal ~/ item.quantity}", 
                  style: GoogleFonts.inter(color: AppColors.primary, fontWeight: FontWeight.w900, fontSize: 15)
                ),
              ],
            ),
          ),
          // QUANTITY AREA
          Row(
            children: [
              _qtyBtn(Icons.remove, const Color(0xFFF5EFE6), const Color(0xFF8B7A6A), () {
                notifier.updateQuantity(item.id, item.quantity - 1);
              }),
              Container(
                constraints: const BoxConstraints(minWidth: 30),
                alignment: Alignment.center,
                padding: const EdgeInsets.symmetric(horizontal: 4),
                child: Text("${item.quantity}", style: GoogleFonts.inter(fontWeight: FontWeight.w900, fontSize: 15)),
              ),
              _qtyBtn(Icons.add, AppColors.primary, Colors.white, () {
                notifier.updateQuantity(item.id, item.quantity + 1);
              }),
            ],
          ),
        ],
      ),
    );
  }

  Widget _qtyBtn(IconData icon, Color bg, Color ic, VoidCallback onTap) {
    return Container(
      width: 40, height: 40,
      decoration: BoxDecoration(color: bg, shape: BoxShape.circle),
      child: IconButton(
        onPressed: onTap,
        icon: Icon(icon, color: ic, size: 20),
        padding: EdgeInsets.zero,
        constraints: const BoxConstraints(),
      ),
    );
  }
}
