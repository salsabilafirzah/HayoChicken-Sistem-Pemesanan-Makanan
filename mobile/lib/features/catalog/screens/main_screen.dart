import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../../core/theme/app_theme.dart';
import 'home_screen.dart';
import 'favorites_screen.dart';
import '../../auth/screens/profile_screen.dart';
import '../../cart/providers/cart_provider.dart';
import '../../cart/screens/cart_screen.dart';
import '../../orders/providers/order_provider.dart';

class MainScreen extends ConsumerStatefulWidget {
  const MainScreen({super.key});

  @override
  ConsumerState<MainScreen> createState() => _MainScreenState();
}

class _MainScreenState extends ConsumerState<MainScreen> {
  int _selectedIndex = 0;

  final List<Widget> _pages = [
    const HomeScreen(),
    const SizedBox.shrink(), // Cart is now a pushed route
    const SizedBox.shrink(), // Favorites is now a pushed route
    const ProfileScreen(),
  ];

  @override
  Widget build(BuildContext context) {
    final cartState = ref.watch(cartProvider);

    return Scaffold(
      extendBody: true,
      resizeToAvoidBottomInset: false,
      body: Stack(
        children: [
          // Mapping index for 4 pages
          _pages[_selectedIndex],
          
          // GLOBAL STICKY ORDER BAR (Dynamic)
          ref.watch(activeOrdersProvider).when(
            data: (activeOrders) {
              if (activeOrders.isEmpty) return const SizedBox.shrink();
              
              return Positioned(
                left: 20, right: 20, bottom: 110,
                child: GestureDetector(
                  onTap: () => _showActiveOrdersModal(context, activeOrders),
                  child: Container(
                    padding: const EdgeInsets.symmetric(horizontal: 18, vertical: 14),
                    decoration: BoxDecoration(
                      color: AppColors.primary,
                      borderRadius: BorderRadius.circular(22),
                      boxShadow: [BoxShadow(color: AppColors.primary.withOpacity(0.4), blurRadius: 15, offset: const Offset(0, 8))],
                    ),
                    child: Row(
                      children: [
                        Container(
                          padding: const EdgeInsets.all(8),
                          decoration: BoxDecoration(color: Colors.white.withOpacity(0.2), borderRadius: BorderRadius.circular(10)),
                          child: const Icon(Icons.timer_outlined, color: Colors.white, size: 24),
                        ),
                        const SizedBox(width: 14),
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text("${activeOrders.length} Pesanan Aktif", style: GoogleFonts.inter(color: Colors.white, fontWeight: FontWeight.w900, fontSize: 13)),
                              Text("Pantau Status Pesananmu", style: GoogleFonts.inter(color: Colors.white70, fontSize: 11, fontWeight: FontWeight.w600)),
                            ],
                          ),
                        ),
                        const Icon(Icons.chevron_right, color: Colors.white, size: 20),
                      ],
                    ),
                  ),
                ),
              );
            },
            loading: () => const SizedBox.shrink(),
            error: (_, __) => const SizedBox.shrink(),
          ),

          // NAVBAR (Pill Style)
          Positioned(
            left: 20, right: 20, bottom: 25,
            child: Container(
              height: 75,
              decoration: BoxDecoration(
                color: const Color(0xFFEBE0D0),
                borderRadius: BorderRadius.circular(40),
                boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.1), blurRadius: 20, offset: const Offset(0, 10))],
              ),
              child: ClipRRect(
                borderRadius: BorderRadius.circular(40),
                child: BottomNavigationBar(
                  type: BottomNavigationBarType.fixed,
                  backgroundColor: Colors.transparent,
                  elevation: 0,
                  selectedItemColor: AppColors.primary,
                  unselectedItemColor: const Color(0xFFBBAA99),
                  currentIndex: _selectedIndex,
                  onTap: (index) {
                    if (index == 1) {
                      context.push('/cart');
                    } else if (index == 2) {
                      context.push('/favorites');
                    } else {
                      setState(() => _selectedIndex = index);
                    }
                  },
                  showSelectedLabels: false,
                  showUnselectedLabels: false,
                  selectedFontSize: 0,
                  unselectedFontSize: 0,
                  items: [
                    _buildNavItem(Icons.home_filled, Icons.home_outlined, 0),
                    _buildCartNavItem(cartState.totalCount), // Triggers full screen on click
                    _buildNavItem(Icons.favorite, Icons.favorite_border, 2),
                    _buildNavItem(Icons.person, Icons.person_outline, 3),
                  ],
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }

  BottomNavigationBarItem _buildNavItem(IconData activeIcon, IconData inactiveIcon, int index) {
    bool isSelected = _selectedIndex == index;
    return BottomNavigationBarItem(
      icon: Container(
        padding: const EdgeInsets.all(12),
        decoration: isSelected ? const BoxDecoration(color: AppColors.primary, shape: BoxShape.circle) : null,
        child: Icon(isSelected ? activeIcon : inactiveIcon, color: isSelected ? Colors.white : const Color(0xFFBBAA99), size: 26),
      ),
      label: "",
    );
  }

  BottomNavigationBarItem _buildCartNavItem(int count) {
    bool isSelected = _selectedIndex == 1; // Visual check for cart
    return BottomNavigationBarItem(
      icon: Stack(
        children: [
          Container(
            padding: const EdgeInsets.all(12),
            decoration: isSelected ? const BoxDecoration(color: AppColors.primary, shape: BoxShape.circle) : null,
            child: Icon(isSelected ? Icons.shopping_cart : Icons.shopping_cart_outlined, color: isSelected ? Colors.white : const Color(0xFFBBAA99), size: 26),
          ),
          if (count > 0)
            Positioned(
              right: 2, top: 2,
              child: Container(
                padding: const EdgeInsets.all(4),
                decoration: const BoxDecoration(color: Colors.orange, shape: BoxShape.circle),
                constraints: const BoxConstraints(minWidth: 18, minHeight: 18),
                child: Text("$count", style: const TextStyle(color: Colors.white, fontSize: 10, fontWeight: FontWeight.bold), textAlign: TextAlign.center),
              ),
            ),
        ],
      ),
      label: "",
    );
  }

  void _showActiveOrdersModal(BuildContext context, List<dynamic> activeOrders) {
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.transparent,
      isScrollControlled: true,
      builder: (context) => Container(
        padding: const EdgeInsets.all(24),
        decoration: const BoxDecoration(
          color: Color(0xFFF8EFDE),
          borderRadius: BorderRadius.only(topLeft: Radius.circular(30), topRight: Radius.circular(30)),
        ),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text("Pesananku", style: GoogleFonts.inter(fontSize: 20, fontWeight: FontWeight.w900)),
                TextButton(onPressed: () => Navigator.pop(context), child: Text("Tutup", style: GoogleFonts.inter(color: AppColors.primary, fontWeight: FontWeight.bold))),
              ],
            ),
            const SizedBox(height: 20),
            ...activeOrders.map((order) {
              Color statusColor = Colors.orange;
              String statusLabel = "Menunggu";
              
              if (order['status'] == 'PROCESSING') {
                statusColor = Colors.blue;
                statusLabel = "Sedang Dimasak";
              } else if (order['status'] == 'DELIVERING') {
                statusColor = Colors.green;
                statusLabel = "Dikirim";
              }

              return InkWell(
                onTap: () {
                  Navigator.pop(context);
                  context.push('/order-status', extra: order['order_number']);
                },
                child: _buildOrderItem(order['order_number'], statusLabel, statusColor),
              );
            }).toList(),
            const SizedBox(height: 20),
          ],
        ),
      ),
    );
  }

  Widget _buildOrderItem(String id, String status, Color color) {
    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(16)),
      child: Row(
        children: [
          Container(
            padding: const EdgeInsets.all(10),
            decoration: BoxDecoration(color: AppColors.primary.withOpacity(0.05), borderRadius: BorderRadius.circular(12)),
            child: const Icon(Icons.shopping_bag_outlined, color: AppColors.primary),
          ),
          const SizedBox(width: 16),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text("Pesanan $id", style: GoogleFonts.inter(fontWeight: FontWeight.w700, fontSize: 14)),
                Text(status, style: GoogleFonts.inter(color: color, fontSize: 12, fontWeight: FontWeight.w700)),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
