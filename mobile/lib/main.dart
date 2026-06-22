import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import 'features/auth/screens/splash_screen.dart';
import 'features/auth/screens/login_screen.dart';
import 'features/auth/screens/register_screen.dart';
import 'features/auth/screens/reset_password_screen.dart';
import 'features/catalog/screens/main_screen.dart';
import 'features/catalog/screens/product_detail_screen.dart';
import 'features/orders/screens/checkout_screen.dart';
import 'features/cart/screens/cart_screen.dart';
import 'features/cart/models/cart_model.dart';
import 'features/profile/screens/order_history_screen.dart';
import 'features/profile/screens/saved_addresses_screen.dart';
import 'features/profile/screens/active_orders_detail_screen.dart';
import 'features/profile/screens/notifications_screen.dart';
import 'features/profile/screens/change_password_screen.dart';
import 'features/auth/screens/edit_profile_screen.dart';
import 'features/catalog/screens/category_screen.dart';
import 'features/catalog/screens/favorites_screen.dart';
import 'features/catalog/screens/search_screen.dart';
import 'features/seller/screens/seller_main_screen.dart';
import 'features/orders/screens/order_success_screen.dart';
import 'features/orders/screens/order_status_screen.dart';
import 'core/theme/app_theme.dart';

void main() {
  runApp(
    const ProviderScope(
      child: HayoChickenApp(),
    ),
  );
}

class HayoChickenApp extends StatelessWidget {
  const HayoChickenApp({super.key});

  @override
  Widget build(BuildContext context) {
    final GoRouter _router = GoRouter(
      initialLocation: '/splash',
      routes: [
        GoRoute(path: '/splash', builder: (context, state) => const SplashScreen()),
        GoRoute(path: '/login', builder: (context, state) => const LoginScreen()),
        GoRoute(path: '/register', builder: (context, state) => const RegisterScreen()),
        GoRoute(path: '/reset-password', builder: (context, state) => const ResetPasswordScreen()),
        GoRoute(path: '/home', builder: (context, state) => const MainScreen()),
        GoRoute(path: '/search', builder: (context, state) => const SearchScreen()),
        GoRoute(
          path: '/category/:id/:name', 
          builder: (context, state) => CategoryScreen(
            categoryId: int.parse(state.pathParameters['id']!), 
            categoryName: state.pathParameters['name']!
          )
        ),
        GoRoute(path: '/cart', builder: (context, state) => const CartScreen()),
        GoRoute(
          path: '/product/:id', 
          builder: (context, state) {
            final extra = state.extra;
            final from = state.uri.queryParameters['from'];
            return ProductDetailScreen(
              productId: int.parse(state.pathParameters['id']!),
              editingCartItem: extra is CartItemModel ? extra : null,
              fromFavorites: from == 'favorites',
            );
          }
        ),
        GoRoute(path: '/checkout', builder: (context, state) => const CheckoutScreen()),
        
        // PROFILE DETAIL ROUTES
        GoRoute(path: '/profile/history', builder: (context, state) => const OrderHistoryScreen()),
        GoRoute(path: '/profile/addresses', builder: (context, state) => const SavedAddressesScreen()),
        GoRoute(path: '/profile/orders', builder: (context, state) => const ActiveOrdersDetailScreen()),
        GoRoute(path: '/profile/notif', builder: (context, state) => const NotificationsScreen()),
        GoRoute(path: '/profile/password', builder: (context, state) => const ChangePasswordScreen()),
        GoRoute(path: '/profile/edit', builder: (context, state) => const EditProfileScreen()),
        GoRoute(path: '/favorites', builder: (context, state) => const FavoritesScreen()),

        GoRoute(path: '/seller/dashboard', builder: (context, state) => const SellerMainScreen()),
        GoRoute(
          path: '/order-success', 
          builder: (context, state) {
            final extras = state.extra as Map<String, dynamic>? ?? {};
            return OrderSuccessScreen(
              orderId: extras['order_number']?.toString() ?? "#HC-20260611-0001",
              totalAmount: "Rp${extras['total_amount']?.toString() ?? '48.000'}",
            );
          }
        ),
        GoRoute(
          path: '/order-status', 
          builder: (context, state) => OrderStatusScreen(
            orderId: state.extra as String? ?? "#HC-20260611-0001",
          )
        ),
      ],
    );

    return MaterialApp.router(
      title: 'Hayo Chicken',
      debugShowCheckedModeBanner: false,
      theme: AppTheme.light,
      routerConfig: _router,
    );
  }
}
