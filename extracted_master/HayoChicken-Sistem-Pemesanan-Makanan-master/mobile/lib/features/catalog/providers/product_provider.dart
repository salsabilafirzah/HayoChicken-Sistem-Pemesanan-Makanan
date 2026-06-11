import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../models/product_model.dart';
import '../services/product_service.dart';

class ProductState {
  final List<ProductModel> products;
  final List<CategoryModel> categories;
  final int? selectedCategoryId;
  final bool isLoading;
  final String? searchQuery;

  ProductState({
    this.products = const [],
    this.categories = const [],
    this.selectedCategoryId,
    this.isLoading = false,
    this.searchQuery,
  });

  ProductState copyWith({
    List<ProductModel>? products,
    List<CategoryModel>? categories,
    int? selectedCategoryId,
    bool? isLoading,
    String? searchQuery,
  }) {
    return ProductState(
      products: products ?? this.products,
      categories: categories ?? this.categories,
      selectedCategoryId: selectedCategoryId ?? this.selectedCategoryId,
      isLoading: isLoading ?? this.isLoading,
      searchQuery: searchQuery ?? this.searchQuery,
    );
  }
}

class ProductNotifier extends StateNotifier<ProductState> {
  final ProductService _service = ProductService();

  ProductNotifier() : super(ProductState()) {
    init();
  }

  Future<void> init() async {
    state = state.copyWith(isLoading: true);
    final cats = await _service.getCategories();
    state = state.copyWith(categories: cats);
    await fetchProducts();
  }

  Future<void> fetchProducts() async {
    state = state.copyWith(isLoading: true);
    final result = await _service.getProducts(
      categoryId: state.selectedCategoryId,
      search: state.searchQuery,
    );
    state = state.copyWith(products: result['products'], isLoading: false);
  }

  void selectCategory(int? id) {
    if (state.selectedCategoryId == id) {
      state = state.copyWith(selectedCategoryId: null);
    } else {
      state = state.copyWith(selectedCategoryId: id);
    }
    fetchProducts();
  }

  void search(String query) {
    state = state.copyWith(searchQuery: query);
    fetchProducts();
  }
}

final productProvider = StateNotifierProvider<ProductNotifier, ProductState>((ref) {
  return ProductNotifier();
});
