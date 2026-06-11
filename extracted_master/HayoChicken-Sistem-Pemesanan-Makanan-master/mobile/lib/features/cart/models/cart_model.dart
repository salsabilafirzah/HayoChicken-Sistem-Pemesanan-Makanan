import '../../catalog/models/product_model.dart';

class CartItemModel {
  final int id;
  final int userId;
  final int productId;
  final int quantity;
  final List<String> selectedExtras;
  final bool isChecked;
  final String? note;
  final ProductModel? product;

  CartItemModel({
    required this.id,
    required this.userId,
    required this.productId,
    required this.quantity,
    required this.selectedExtras,
    required this.isChecked,
    this.note,
    this.product,
  });

  factory CartItemModel.fromJson(Map<String, dynamic> json) {
    return CartItemModel(
      id: json['id'],
      userId: json['user_id'],
      productId: json['product_id'],
      quantity: json['quantity'] is String ? int.parse(json['quantity']) : json['quantity'],
      selectedExtras: (json['selected_extras_snapshot'] as List? ?? []).map((e) => e.toString()).toList(),
      isChecked: json['is_checked'] == 1 || json['is_checked'] == true,
      note: json['note'],
      product: json['product'] != null ? ProductModel.fromJson(json['product']) : null,
    );
  }

  int get subtotal {
    if (product == null) return 0;
    
    int extrasPrice = 0;
    if (product!.extras.isNotEmpty) {
      for (var extraName in selectedExtras) {
        final extra = product!.extras.firstWhere(
          (e) => e.name == extraName, 
          orElse: () => ProductExtraModel(id: 0, name: '', additionalPrice: 0)
        );
        extrasPrice += extra.additionalPrice;
      }
    }
    
    return (product!.basePrice + extrasPrice) * quantity;
  }
}
