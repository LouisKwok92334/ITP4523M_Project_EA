from flask import Flask, jsonify, request

app = Flask(__name__)

@app.route('/api/discountCalculator', methods=['GET'])
def discount_calculator():
    try:
        total_order_amount = float(request.args.get('TotalOrderAmount'))
    except (TypeError, ValueError):
        return jsonify({"error": "Invalid input for Total Order Amount"}), 400

    discount_rate = 0.0

    if total_order_amount >= 10000:
        discount_rate = 0.13
    elif total_order_amount >= 5000:
        discount_rate = 0.06
    elif total_order_amount >= 3000:
        discount_rate = 0.03
    else:
        discount_rate = 0.0

    new_order_amount = total_order_amount * (1 - discount_rate)

    return jsonify({"DiscountRate": discount_rate, "NewOrderAmount": new_order_amount})

if __name__ == "__main__":
    app.run(host='0.0.0.0', port=8000)