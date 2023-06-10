from flask import Flask, request, jsonify

app = Flask(__name__)

@app.route('/api/discountCalculator', methods=['GET'])
def discount_calculator():
    total_order_amount = float(request.args.get('Total Order Amount', 0))

    if total_order_amount >= 10000:
        discount_rate = 0.13
    elif total_order_amount >= 5000:
        discount_rate = 0.06
    elif total_order_amount >= 3000:
        discount_rate = 0.03
    else:
        discount_rate = 0

    new_order_amount = total_order_amount - (total_order_amount * discount_rate)

    return jsonify({'DiscountRate': discount_rate, 'NewOrderAmount': new_order_amount})

if __name__ == "__main__":
    app.run(host='0.0.0.0', port=80)
