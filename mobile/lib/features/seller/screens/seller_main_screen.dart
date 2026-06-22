import 'package:flutter/material.dart';
import 'seller_order_list_screen.dart';
import 'seller_product_list_screen.dart';
import 'seller_analytics_screen.dart';
import '../../../core/theme/app_theme.dart';

class SellerMainScreen extends StatefulWidget {
  const SellerMainScreen({super.key});

  @override
  State<SellerMainScreen> createState() => _SellerMainScreenState();
}

class _SellerMainScreenState extends State<SellerMainScreen> {
  int _currentIndex = 0;

  final List<Widget> _pages = [
    const SellerOrderListScreen(),
    const SellerProductListScreen(),
    const SellerAnalyticsScreen(),
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF8EFDE),
      extendBody: true,
      body: IndexedStack(
        index: _currentIndex,
        children: _pages,
      ),
      bottomNavigationBar: Container(
        height: 90,
        margin: const EdgeInsets.all(20),
        decoration: BoxDecoration(
          color: const Color(0xFFEBE0D0),
          borderRadius: BorderRadius.circular(40),
          boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.1), blurRadius: 20, offset: const Offset(0, 10))],
        ),
        child: Row(
          mainAxisAlignment: MainAxisAlignment.spaceAround,
          children: [
            _buildNavItem(0, Icons.assignment, "Pesanan"),
            _buildNavItem(1, Icons.grid_view, "Menu"),
            _buildNavItem(2, Icons.show_chart, "Penjualan"),
          ],
        ),
      ),
    );
  }

  Widget _buildNavItem(int index, IconData icon, String label) {
    bool isSelected = _currentIndex == index;
    return GestureDetector(
      onTap: () => setState(() => _currentIndex = index),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Container(
            padding: const EdgeInsets.all(12),
            decoration: BoxDecoration(
              color: isSelected ? AppColors.primary : Colors.transparent,
              shape: BoxShape.circle,
            ),
            child: Icon(icon, color: isSelected ? Colors.white : const Color(0xFF8B7A6A)),
          ),
          const SizedBox(height: 4),
          Text(label, style: TextStyle(
            fontSize: 10, 
            fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
            color: isSelected ? AppColors.primary : const Color(0xFF8B7A6A)
          )),
        ],
      ),
    );
  }
}
