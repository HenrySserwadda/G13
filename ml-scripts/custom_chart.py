import argparse
import matplotlib.pyplot as plt
import pandas as pd
import os
import json
import sys
import numpy as np

def load_data():
    # Load CSV using an absolute path relative to this script
    script_dir = os.path.dirname(os.path.abspath(__file__))
    csv_path = os.path.join(script_dir, '../database/datasets/bags_from_durabag.csv')
    print('Looking for CSV at:', csv_path)
    return pd.read_csv(csv_path)

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
    parser.add_argument('--chart_type', required=True, choices=['bar', 'line', 'pie'])
    parser.add_argument('--x_axis', required=True)
    parser.add_argument('--x_axis2', required=False, default=None)
    parser.add_argument('--y_axis', required=True)
    parser.add_argument('--output', required=False)
    parser.add_argument('--json', action='store_true', help='Output chart data as JSON')
    args = parser.parse_args()

    df = load_data()

    group_cols = [args.x_axis]
    if args.x_axis2 and args.x_axis2 != 'None' and args.x_axis2 != '':
        group_cols.append(args.x_axis2)

    # After df = load_data()
    missing_cols = [col for col in group_cols if col not in df.columns]
    if args.y_axis != 'sales' and args.y_axis not in df.columns:
        missing_cols.append(args.y_axis)
    if missing_cols:
        error = {
            "error": f"Missing columns in CSV: {', '.join(missing_cols)}",
            "type": None,
            "labels": [],
            "values": []
        }
        if args.json:
            print(json.dumps(error))
            sys.exit(0)
        else:
            print(f"Error: Missing columns in CSV: {', '.join(missing_cols)}")
            sys.exit(1)

    month_order = ['January','February','March','April','May','June','July','August','September','October','November','December']
    # Prepare data for chart
    if args.y_axis == 'sales':
        # Frequency count logic
        if args.chart_type == 'pie':
            if args.x_axis == 'Month of the year':
                data = df.groupby(group_cols).size().reindex(month_order).fillna(0)
                labels = list(data.index)
                values = list(data.values)
            else:
                data = df.groupby(group_cols).size()
                labels = []
                values = []
                for idx, val in data.items():
                    if isinstance(idx, tuple):
                        label = ' - '.join(str(i) for i in idx)
                    else:
                        label = str(idx)
                    labels.append(label)
                    values.append(val)
            chart_data = {
                'type': 'pie',
                'labels': labels,
                'values': values
            }
        else:
            if args.x_axis == 'Month of the year':
                data = df.groupby(group_cols).size().reindex(month_order).fillna(0)
                labels = list(data.index)
                values = list(data.values)
                chart_data = {
                    'type': args.chart_type,
                    'labels': [str(l) for l in labels],
                    'values': values
                }
            else:
                data = df.groupby(group_cols).size()
                if len(group_cols) == 2:
                    data = data.unstack()
                    labels = list(data.index)
                    datasets = []
                    for col in data.columns:
                        datasets.append({
                            'label': str(col),
                            'data': [v if not pd.isna(v) else 0 for v in data[col]]
                        })
                    chart_data = {
                        'type': args.chart_type,
                        'labels': [str(l) for l in labels],
                        'datasets': datasets
                    }
                else:
                    labels = list(data.index)
                    values = list(data.values)
                    chart_data = {
                        'type': args.chart_type,
                        'labels': [str(l) for l in labels],
                        'values': values
                    }
    else:
        if args.chart_type == 'pie':
            if args.x_axis == 'Month of the year':
                data = df.groupby(group_cols)[args.y_axis].sum().reindex(month_order).fillna(0)
                labels = list(data.index)
                values = list(data.values)
            else:
                data = df.groupby(group_cols)[args.y_axis].sum()
                labels = []
                values = []
                for idx, val in data.items():
                    if isinstance(idx, tuple):
                        label = ' - '.join(str(i) for i in idx)
                    else:
                        label = str(idx)
                    labels.append(label)
                    values.append(val)
            chart_data = {
                'type': 'pie',
                'labels': labels,
                'values': values
            }
        else:
            if args.x_axis == 'Month of the year':
                data = df.groupby(group_cols)[args.y_axis].sum().reindex(month_order).fillna(0)
                labels = list(data.index)
                values = list(data.values)
                chart_data = {
                    'type': args.chart_type,
                    'labels': [str(l) for l in labels],
                    'values': values
                }
            else:
                data = df.groupby(group_cols)[args.y_axis].sum()
                if len(group_cols) == 2 and args.x_axis == 'Month of the year':
                    data = data.unstack()
                    data = data.reindex(month_order).fillna(0)
                    labels = list(data.index)
                    datasets = []
                    for col in data.columns:
                        datasets.append({
                            'label': str(col),
                            'data': [v if not pd.isna(v) else 0 for v in data[col]]
                        })
                    chart_data = {
                        'type': args.chart_type,
                        'labels': [str(l) for l in labels],
                        'datasets': datasets
                    }
                elif len(group_cols) == 2:
                    data = data.unstack()
                    labels = list(data.index)
                    datasets = []
                    for col in data.columns:
                        datasets.append({
                            'label': str(col),
                            'data': [v if not pd.isna(v) else 0 for v in data[col]]
                        })
                    chart_data = {
                        'type': args.chart_type,
                        'labels': [str(l) for l in labels],
                        'datasets': datasets
                    }
                else:
                    labels = list(data.index)
                    values = list(data.values)
                    chart_data = {
                        'type': args.chart_type,
                        'labels': [str(l) for l in labels],
                        'values': values
                    }

    if args.json:
        print(json.dumps(to_py(chart_data)))
        sys.exit(0)

    # Only generate and save image if output is provided and not using --json
    if args.output and not args.json:
        if args.chart_type == 'pie':
            plt.figure(figsize=(7,7))
            # For two groupings, show as a pie of the first group, colored by the second
            if len(group_cols) == 2:
                def labeler(i):
                    if isinstance(i, tuple):
                        return f'{i[0]} - {i[1]}'
                    else:
                        return str(i)
                data.index = [labeler(i) for i in data.index]
            data.plot.pie(autopct='%1.1f%%', startangle=90)
            plt.ylabel('')
            plt.title(f'{args.y_axis.title()} by {" and ".join(group_cols)}')
        else:
            data = df.groupby(group_cols)[args.y_axis].sum().unstack() if len(group_cols) == 2 else df.groupby(group_cols)[args.y_axis].sum().reset_index()
            plt.figure(figsize=(10,6))
            if args.chart_type == 'bar':
                if len(group_cols) == 2:
                    data.plot(kind='bar')
                else:
                    plt.bar(data[args.x_axis], data[args.y_axis])
            elif args.chart_type == 'line':
                if len(group_cols) == 2:
                    data.plot(kind='line')
                else:
                    plt.plot(data[args.x_axis], data[args.y_axis], marker='o')
            plt.xlabel(args.x_axis.title() + (f' and {args.x_axis2.title()}' if args.x_axis2 and args.x_axis2 != 'None' and args.x_axis2 != '' else ''))
            plt.ylabel(args.y_axis.title())
            plt.title(f'{args.y_axis.title()} by {" and ".join(group_cols)}')
            plt.xticks(rotation=45)

        plt.tight_layout()
        # Ensure output directory exists
        os.makedirs(os.path.dirname(args.output), exist_ok=True)
        plt.savefig(args.output)
        plt.close()

if __name__ == '__main__':
    try:
        main()
    except Exception as e:
        error = {
            "error": f"Exception: {str(e)}",
            "type": None,
            "labels": [],
            "values": []
        }
        # Always print JSON error if --json is in sys.argv
        if '--json' in sys.argv:
            print(json.dumps(error))
        else:
            print(f"Exception: {str(e)}")
        sys.exit(0)