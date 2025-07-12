import pandas as pd
import matplotlib.pyplot as plt
import seaborn as sns
import os
from datetime import datetime

def generate_sales_analytics():
    df = pd.read_csv(os.path.join(os.path.dirname(__file__), '../database/datasets/bags_from_durabag.csv'))
    
    # Ensure output directory exists before saving images
    images_dir = os.path.join(os.path.dirname(__file__), '../public/images')
    os.makedirs(images_dir, exist_ok=True)
    
    # Sales by month
    plt.figure(figsize=(10, 6))
    monthly_sales = df['Likely Purchase Month'].value_counts().sort_index()
    monthly_sales.plot(kind='bar')
    plt.title('Sales by Month')
    plt.xlabel('Month')
    plt.ylabel('Number of Sales')
    plt.savefig(os.path.join(images_dir, 'sales_by_month.png'))
    plt.close()
    
    # Sales by material
    plt.figure(figsize=(10, 6))
    df['Material'].value_counts().plot(kind='pie', autopct='%1.1f%%')
    plt.title('Sales by Material')
    plt.savefig(os.path.join(images_dir, 'sales_by_material.png'))
    plt.close()
    
    # Sales by gender
    plt.figure(figsize=(8, 8))
    df['Likely Gender'].value_counts().plot(kind='pie', autopct='%1.1f%%')
    plt.title('Sales by Gender')
    plt.savefig(os.path.join(images_dir, 'sales_by_gender.png'))
    plt.close()
    
    # Return paths to generated images
    return {
        'monthly_sales': 'images/sales_by_month.png',
        'material_sales': 'images/sales_by_material.png',
        'gender_sales': 'images/sales_by_gender.png'
    }

if __name__ == "__main__":
    generate_sales_analytics()