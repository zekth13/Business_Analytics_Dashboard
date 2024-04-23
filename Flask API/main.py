from flask import Flask, request, jsonify
#Really need thesey
import monthly_prediction
import annual_prediction
import str_no_prediction

app = Flask(__name__)

@app.route("/")
def hello():
    return "This is the main page"

@app.route("/monthly")
def monthly():
    month_result = monthly_prediction.forecast_monthly()
    return month_result

@app.route("/annual")
def annual():
    annual_result = annual_prediction.forecast_annually()
    return annual_result

@app.route("/monthstoreprediction", methods=['POST'])
def monthstoreprediction():    
    data_received = request.json.get('data')

    store_result = str_no_prediction.monthstoreprediction(data_received)

    # Process the received data
    print(f"Received data: {data_received}")

    return store_result

@app.route("/yearstoreprediction", methods=['POST'])
def yearstoreprediction():    
    data_received = request.json.get('data')

    store_result = str_no_prediction.yearstoreprediction(data_received)

    # Process the received data
    print(f"Received data: {data_received}")

    return store_result

@app.route("/test")
def test():
    data_received = '101'
    store_result = str_no_prediction.monthstoreprediction(data_received)

    # Process the received data
    print(f"Received data: {data_received}")

    return store_result

if __name__ == "__main__":
    app.run(debug=True)