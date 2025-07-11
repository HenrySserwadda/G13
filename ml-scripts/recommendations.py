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
                                'Waterproof', 'Style', 'Color', 'Gender']])
    
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
                                'Waterproof', 'Style', 'Color', 'Gender']])
    
    # Scale the features
    product_features = features.loc[product.name].values.reshape(1, -1)
    scaled_features = scaler.transform(product_features)
    
    # Find nearest neighbors
    distances, indices = model.kneighbors(scaled_features, n_neighbors=num_recommendations+1)
    
    # Get recommended products (excluding the product itself)
    recommendations = df.iloc[indices[0][1:num_recommendations+1]]
    
    return recommendations.to_dict('records')

def get_product_data(user_gender=None, preferred_styles=None, preferred_colors=None):
    """
    Returns a list of product dicts with fields:
    - name: Style + Compartments
    - description: Color + Material + Style
    - price: Weight * 10000 (int)
    - quantity: Compartments
    - image: path to image file (database/datasets/images/<Image>)
    Optionally filters by user_gender, preferred_styles, preferred_colors.
    """
    df = pd.read_csv(os.path.join(os.path.dirname(__file__), '../database/datasets/bags_from_durabag.csv'))
    # Fill missing values
    df['Material'] = df['Material'].fillna('Unknown')
    df['Color'] = df['Color'].fillna('Unknown')
    df['Style'] = df['Style'].fillna('Unknown')
    df['Compartments'] = df['Compartments'].fillna('1')
    df['Weight Capacity (kg)'] = df['Weight Capacity (kg)'].fillna(1)
    df['Image'] = df['Image'].fillna('')
    df['Gender'] = df['Gender'].fillna('Unisex')

    # Ensure df is a DataFrame (not accidentally converted to ndarray)
    if not isinstance(df, pd.DataFrame):
        df = pd.DataFrame(df)
    # Filtering
    if user_gender:
        df = df[df['Gender'].astype(str).str.lower().str.contains(str(user_gender).lower())]
    if preferred_styles:
        df = df[df['Style'].astype(str).str.lower().isin([s.lower() for s in preferred_styles])]
    if preferred_colors:
        df = df[df['Color'].astype(str).str.lower().isin([c.lower() for c in preferred_colors])]

    # Limit to 10 random products
    if len(df) > 10:
        df = df.sample(n=10, random_state=None)

    products = []
    for _, row in df.iterrows():
        name = f"{row['Style']} {row['Compartments']}"
        description = f"{row['Color']} {row['Material']} {row['Style']}"
        try:
            price = int(float(row['Weight Capacity (kg)']) * 10000)
        except Exception:
            price = 10000
        try:
            quantity = int(row['Compartments'])
        except Exception:
            quantity = 1
        filename = str(row['Image']).strip().replace('images/', '').replace('\\', '/').replace('\\', '/').lstrip('/')
        image = os.path.join('images/dataset', filename).replace('\\', '/').replace('\\', '/')
        products.append({
            'id': row['id'],
            'name': name,
            'description': description,
            'price': price,
            'quantity': quantity,
            'image': image,
            'style': row['Style'],
            'color': row['Color'],
            'gender': row['Gender']
        })
    return products

# For CLI testing
if __name__ == "__main__":
    import argparse
    import json
    parser = argparse.ArgumentParser()
    parser.add_argument('--get_products', action='store_true', help='Output product data for ML')
    parser.add_argument('--gender', type=str, default=None)
    parser.add_argument('--styles', type=str, default=None, help='Comma-separated styles')
    parser.add_argument('--colors', type=str, default=None, help='Comma-separated colors')
    args = parser.parse_args()

    if args.get_products:
        styles = [s.strip() for s in args.styles.split(',')] if args.styles else None
        colors = [c.strip() for c in args.colors.split(',')] if args.colors else None
        products = get_product_data(user_gender=args.gender, preferred_styles=styles, preferred_colors=colors)
        print(json.dumps(products))
        exit(0)
    else:
        print(train_recommendation_model())
        # Test with a sample product ID
        print(get_recommendations(300000))
        # Test product data extraction
        print(json.dumps(get_product_data(user_gender='female', preferred_styles=['Puffer'], preferred_colors=['Black']), indent=2))