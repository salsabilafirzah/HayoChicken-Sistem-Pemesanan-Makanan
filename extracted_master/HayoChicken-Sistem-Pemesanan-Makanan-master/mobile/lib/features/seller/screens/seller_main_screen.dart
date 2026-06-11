import 'package:flutter/material.dart';
import 'seller_order_list_screen.dart';
import 'seller_product_list_screen.dart';
import 'seller_analytics_screen.dart';

class SellerMainScreen extends StatefulWidget {
  const SellerMainScreen({super.key});

  @override
  State<SellerMainScreen> createState() => _SellerMainScreenState();
}

class _SellerMainScreenState extends State<SellerMainScreen> {
  int _currentIndex = 0;

  final List<Widget> _screens = [
    const SellerOrderListScreen(),
    const SellerProductListScreen(),
    const SellerAnalyticsScreen(),
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: _screens[_currentIndex],
      bottomNavigationBar: BottomNavigationBar(
        currentIndex: _currentIndex,
        onTap: (index) => setState(() => _currentIndex = index),
        selectedItemColor: Colors.red,
        items: const [
          BottomNavigationBarItem(icon: Icon(Icons.receipt_long), label: "Pesanan"),
          BottomNavigationBarItem(icon: Icon(Icons.restaurant_menu), label: "Menu"),
          BottomNavigationBarItem(icon: Icon(Icons.bar_chart), label: "Penjualan"),
        ],
      ),
    );
  }
}
