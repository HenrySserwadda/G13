import pandas as pd
from sklearn.preprocessing import LabelEncoder
from sklearn.model_selection import train_test_split
import joblib
import os

def load_and_preprocess_data():
    # Load the dataset
    df = pd.read_csv(os.path.join(os.path.dirname(__file__), '../database/datasets/bags_from_durabag.csv'))
    
    # Handle missing values (avoid chained assignment warnings)
    df['Material'] = df['Material'].fillna('Unknown')
    df['Color'] = df['Color'].fillna('Unknown')
    df['Style'] = df['Style'].fillna('Unknown')
    df['Likely Gender'] = df['Likely Gender'].fillna('Unisex')
    df['Weight Capacity (kg)'] = df['Weight Capacity (kg)'].fillna(df['Weight Capacity (kg)'].median())
    
    # Convert categorical features
    categorical_cols = ['Material', 'Size', 'Color', 'Style', 'Likely Gender', 'Likely Purchase Month']
    label_encoders = {}
    
    for col in categorical_cols:
        le = LabelEncoder()
        df[col] = le.fit_transform(df[col].astype(str))
        label_encoders[col] = le
    
    # Ensure output directory exists before saving
    os.makedirs(os.path.join(os.path.dirname(__file__), '../storage/app/ml-models'), exist_ok=True)
    joblib.dump(label_encoders, os.path.join(os.path.dirname(__file__), '../storage/app/ml-models/label_encoders.joblib'))
    
    return df

if __name__ == "__main__":
    df = load_and_preprocess_data()
    print(df.head())