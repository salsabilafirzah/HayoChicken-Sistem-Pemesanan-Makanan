import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../models/product_model.dart';
import '../models/category_model.dart';
import '../services/product_service.dart';

class ProductState {
  final List<ProductModel> products;
  final List<CategoryModel> categories;
  final List<int> favoriteIds;
  final bool isLoading;

  ProductState({
    this.products = const [],
    this.categories = const [],
    this.favoriteIds = const [],
    this.isLoading = false,
  });

  ProductState copyWith({
    List<ProductModel>? products,
    List<CategoryModel>? categories,
    List<int>? favoriteIds,
    bool? isLoading,
  }) {
    return ProductState(
      products: products ?? this.products,
      categories: categories ?? this.categories,
      favoriteIds: favoriteIds ?? this.favoriteIds,
      isLoading: isLoading ?? this.isLoading,
    );
  }
}

class ProductNotifier extends StateNotifier<ProductState> {
  final ProductService _service = ProductService();

  ProductNotifier() : super(ProductState()) {
    loadData();
  }

  Future<void> loadData() async {
    state = state.copyWith(isLoading: true);
    
    List<ProductModel> products = [];
    List<CategoryModel> categories = [];
    List<int> favorites = [];

    try {
      products = await _service.getProducts();
    } catch (e) {
      // Allow partial failure
    }
    
    try {
      categories = await _service.getCategories();
    } catch (e) {
      // Allow partial failure
    }
    
    try {
      favorites = await _service.getFavorites();
    } catch (e) {
      // Allow partial failure
    }
    
    state = state.copyWith(
      products: products,
      categories: categories,
      favoriteIds: favorites,
      isLoading: false,
    );
  }

  Future<void> toggleFavorite(int productId) async {
    // 1. Optimistic Update (Immediate Red Heart)
    final isFav = state.favoriteIds.contains(productId);
    final newFavs = List<int>.from(state.favoriteIds);
    if (isFav) {
      newFavs.remove(productId);
    } else {
      newFavs.add(productId);
    }
    state = state.copyWith(favoriteIds: newFavs);

    // 2. Persistent Backend Sync
    final success = await _service.toggleFavorite(productId);
    
    // 3. Optional: Sync back from server to ensure data integrity
    if (success) {
      final updatedFavs = await _service.getFavorites();
      state = state.copyWith(favoriteIds: updatedFavs);
    }
  }
}

final productProvider = StateNotifierProvider<ProductNotifier, ProductState>((ref) {
  return ProductNotifier();
});
