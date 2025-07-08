import pandas as pd
import numpy as np
import argparse
import json
import sys
import os
from statsmodels.tsa.arima.model import ARIMA
import datetime

month_order = ['January','February','March','April','May','June','July','August','September','October','November','December']

def to_py(obj):
    if isinstance(obj, np.generic):
        return obj.item()
    if isinstance(obj, (list, tuple)):
        return [to_py(x) for x in obj]
    if isinstance(obj, dict):
        return {k: to_py(v) for k, v in obj.items()}
    return obj

def main():
    parser = argparse.ArgumentParser()
    parser.add_argument('--months_ahead', type=int, default=1, help='How many months ahead to predict')
    parser.add_argument('--material', type=str, default=None)
    parser.add_argument('--size', type=str, default=None)
    parser.add_argument('--compartments', type=str, default=None)
    parser.add_argument('--laptop_compartment', type=str, default=None)
    parser.add_argument('--waterproof', type=str, default=None)
    parser.add_argument('--style', type=str, default=None)
    parser.add_argument('--color', type=str, default=None)
    parser.add_argument('--month', type=str, default=None)
    parser.add_argument('--gender', type=str, default=None)
    parser.add_argument('--json', action='store_true')
    args = parser.parse_args()
    try:
        csv_path = os.path.join(os.path.dirname(os.path.abspath(__file__)), '../database/datasets/bags_from_durabag.csv')
        df = pd.read_csv(csv_path)
        # Apply filters if provided
        if args.material:
            df = df[df['Material'] == args.material]
        if args.size:
            df = df[df['Size'] == args.size]
        if args.compartments:
            df = df[df['Compartments'].astype(str) == args.compartments]
        if args.laptop_compartment:
            df = df[df['Laptop Compartment'] == args.laptop_compartment]
        if args.waterproof:
            df = df[df['Waterproof'] == args.waterproof]
        if args.style:
            df = df[df['Style'] == args.style]
        if args.color:
            df = df[df['Color'] == args.color]
        if args.month:
            df = df[df['Month of the year'] == args.month]
        if args.gender:
            df = df[df['Gender'] == args.gender]
        # Ensure df is a DataFrame before groupby
        if not isinstance(df, pd.DataFrame):
            df = pd.DataFrame(df)
        # Aggregate sales by month (sum of Weight Capacity (kg))
        sales_by_month = df.groupby('Month of the year')['Weight Capacity (kg)'].sum().reindex(month_order).fillna(0)
        # ARIMA expects a time series indexed by time
        sales_series = pd.Series(sales_by_month.values, index=pd.Index(month_order, name='Month'))
        # Fit ARIMA model (auto order selection could be added, but we'll use (1,1,1) for simplicity)
        model = ARIMA(sales_series, order=(1,1,1))
        model_fit = model.fit()
        # Forecast
        forecast = model_fit.forecast(steps=args.months_ahead)
        # Real-time: start from the actual next month
        now = datetime.datetime.now()
        current_month_idx = now.month - 1  # 0-based index
        future_months = [month_order[(current_month_idx + i) % 12] for i in range(1, args.months_ahead + 1)]
        result = {
            'future_months': future_months,
            'predictions': [float(p) for p in forecast]
        }
        if args.json:
            print(json.dumps(to_py(result)))
        else:
            print(result)
    except Exception as e:
        error = {
            "error": f"Exception: {str(e)}",
            "future_months": [],
            "predictions": []
        }
        if args.json:
            print(json.dumps(error))
        else:
            print(f"Exception: {str(e)}")
        sys.exit(0)

if __name__ == '__main__':
    main() 