import argparse
import matplotlib.pyplot as plt
import pandas as pd
import os

def load_data():
    # Load CSV using an absolute path relative to this script
    script_dir = os.path.dirname(os.path.abspath(__file__))
    csv_path = os.path.join(script_dir, '../database/datasets/bags_from_durabag.csv')
    print('Looking for CSV at:', csv_path)
    return pd.read_csv(csv_path)

def main():
    parser = argparse.ArgumentParser()
    parser.add_argument('--chart_type', required=True, choices=['bar', 'line', 'pie'])
    parser.add_argument('--x_axis', required=True)
    parser.add_argument('--x_axis2', required=False, default=None)
    parser.add_argument('--y_axis', required=True)
    parser.add_argument('--output', required=True)
    args = parser.parse_args()

    

    df = load_data()

    group_cols = [args.x_axis]
    if args.x_axis2 and args.x_axis2 != 'None' and args.x_axis2 != '':
        group_cols.append(args.x_axis2)

    if args.chart_type == 'pie':
        data = df.groupby(group_cols)[args.y_axis].sum()
        plt.figure(figsize=(7,7))
        # For two groupings, show as a pie of the first group, colored by the second
        if len(group_cols) == 2:
            # Combine group names for labels, handle int index
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
    main()