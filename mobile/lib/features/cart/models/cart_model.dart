import 'dart:convert';
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

  CartItemModel copyWith({
    int? id,
    int? userId,
    int? productId,
    int? quantity,
    List<String>? selectedExtras,
    bool? isChecked,
    String? note,
    ProductModel? product,
  }) {
    return CartItemModel(
      id: id ?? this.id,
      userId: userId ?? this.userId,
      productId: productId ?? this.productId,
      quantity: quantity ?? this.quantity,
      selectedExtras: selectedExtras ?? this.selectedExtras,
      isChecked: isChecked ?? this.isChecked,
      note: note ?? this.note,
      product: product ?? this.product,
    );
  }

  factory CartItemModel.fromJson(Map<String, dynamic> json) {
    return CartItemModel(
      id: json['id'],
      userId: json['user_id'],
      productId: json['product_id'],
      quantity: json['quantity'] is String ? int.parse(json['quantity']) : json['quantity'],
      selectedExtras: _parseExtras(json['extras'] ?? json['selected_extras_snapshot']),
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
          (e) => e.name.toLowerCase() == extraName.toLowerCase(), 
          orElse: () => ProductExtraModel(id: 0, name: '', additionalPrice: 0)
        );
        extrasPrice += extra.additionalPrice;
      }
    }
    
    return (product!.basePrice + extrasPrice) * quantity;
  }
  static List<String> _parseExtras(dynamic raw) {
    if (raw == null) return [];
    List<dynamic> list = [];
    if (raw is String) {
      try {
        list = jsonDecode(raw);
      } catch (_) {
        return [raw];
      }
    } else if (raw is List) {
      list = raw;
    }
    
    return list.map((e) {
      if (e is Map) return e['name']?.toString() ?? '';
      return e.toString();
    }).where((e) => e.isNotEmpty).toList();
  }
}
