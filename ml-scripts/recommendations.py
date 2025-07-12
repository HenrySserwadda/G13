import pandas as pd
from sklearn.neighbors import NearestNeighbors
from sklearn.preprocessing import StandardScaler
import joblib
import os

def train_recommendation_model():
    df = pd.read_csv(os.path.join(os.path.dirname(__file__), '../database/datasets/bags_from_durabag.csv'))
    
    # Preprocess data (avoid chained assignment warnings)
    df['Material'] = df['Material'].fillna('Unknown')
    df['Color'] = df['Color'].fillna('Unknown')
    df['Style'] = df['Style'].fillna('Unknown')
    
    # One-hot encoding for categorical features
    features = pd.get_dummies(df[['Material', 'Size', 'Compartments', 'Laptop Compartment', 
                                'Waterproof', 'Style', 'Color', 'Likely Gender']])
    
    # Scale features
    scaler = StandardScaler()
    scaled_features = scaler.fit_transform(features)
    
    # Train KNN model
    model = NearestNeighbors(n_neighbors=5, algorithm='auto')
    model.fit(scaled_features)
    
    # Ensure output directory exists before saving
    output_dir = os.path.join(os.path.dirname(__file__), '../storage/app/ml-models')
    os.makedirs(output_dir, exist_ok=True)
    joblib.dump(model, os.path.join(output_dir, 'recommendation_model.joblib'))
    joblib.dump(scaler, os.path.join(output_dir, 'scaler.joblib'))
    df.to_csv(os.path.join(output_dir, 'product_data.csv'), index=False)
    
    return "Model trained and saved successfully"

def get_recommendations(product_id, num_recommendations=5):
    # Load necessary files
    base_dir = os.path.join(os.path.dirname(__file__), '../storage/app/ml-models')
    model = joblib.load(os.path.join(base_dir, 'recommendation_model.joblib'))
    scaler = joblib.load(os.path.join(base_dir, 'scaler.joblib'))
    df = pd.read_csv(os.path.join(base_dir, 'product_data.csv'))
    
    # Get the product features
    product = df[df['id'] == product_id]
    if product.empty:
        return []
    product = product.iloc[0]
    features = pd.get_dummies(df[['Material', 'Size', 'Compartments', 'Laptop Compartment', 
                                'Waterproof', 'Style', 'Color', 'Likely Gender']])
    
    # Scale the features
    product_features = features.loc[product.name].values.reshape(1, -1)
    scaled_features = scaler.transform(product_features)
    
    # Find nearest neighbors
    distances, indices = model.kneighbors(scaled_features, n_neighbors=num_recommendations+1)
    
    # Get recommended products (excluding the product itself)
    recommendations = df.iloc[indices[0][1:num_recommendations+1]]
    
    return recommendations.to_dict('records')

if __name__ == "__main__":
    print(train_recommendation_model())
    # Test with a sample product ID
    print(get_recommendations(300000))