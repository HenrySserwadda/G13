import pandas as pd
from sklearn.neighbors import NearestNeighbors
from sklearn.preprocessing import StandardScaler
import joblib
import os
import sys
import json
import logging

# Set up logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

def train_recommendation_model():
    try:
        dataset_path = os.path.join(os.path.dirname(__file__), '../database/datasets/bags_from_durabag.csv')
        logger.info(f"Loading dataset from: {dataset_path}")
        
        if not os.path.exists(dataset_path):
            logger.error(f"Dataset file not found: {dataset_path}")
            return "Error: Dataset file not found"
        
        df = pd.read_csv(dataset_path)
        logger.info(f"Loaded dataset with {len(df)} rows and {len(df.columns)} columns")
        
        # Preprocess data (avoid chained assignment warnings)
        df['Material'] = df['Material'].fillna('Unknown')
        df['Color'] = df['Color'].fillna('Unknown')
        df['Style'] = df['Style'].fillna('Unknown')
        df['Gender'] = df['Gender'].fillna('Unisex')
        
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
        
        logger.info("Model trained and saved successfully")
        return "Model trained and saved successfully"
        
    except Exception as e:
        logger.error(f"Error training model: {str(e)}")
        return f"Error training model: {str(e)}"

def get_recommendations(product_id, num_recommendations=5):
    try:
        # Load necessary files
        base_dir = os.path.join(os.path.dirname(__file__), '../storage/app/ml-models')
        model = joblib.load(os.path.join(base_dir, 'recommendation_model.joblib'))
        scaler = joblib.load(os.path.join(base_dir, 'scaler.joblib'))
        df = pd.read_csv(os.path.join(base_dir, 'product_data.csv'))
        
        # Get the product features
        product = df[df['id'] == product_id]
        if product.empty:
            logger.warning(f"Product with ID {product_id} not found")
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
        
    except Exception as e:
        logger.error(f"Error getting recommendations: {str(e)}")
        return []

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
    try:
        dataset_path = os.path.join(os.path.dirname(__file__), '../database/datasets/bags_from_durabag.csv')
        logger.info(f"Loading dataset from: {dataset_path}")
        
        if not os.path.exists(dataset_path):
            logger.error(f"Dataset file not found: {dataset_path}")
            return []
        
        df = pd.read_csv(dataset_path)
        logger.info(f"Loaded dataset with {len(df)} rows")
        
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
        
        # Filtering with case-insensitive matching
        if user_gender:
            logger.info(f"Filtering by gender: {user_gender}")
            df = df[df['Gender'].astype(str).str.lower().str.contains(str(user_gender).lower())]
            logger.info(f"After gender filter: {len(df)} rows")
            
        if preferred_styles:
            if isinstance(preferred_styles, str):
                preferred_styles = [s.strip() for s in preferred_styles.split(',')]
            logger.info(f"Filtering by styles: {preferred_styles}")
            df = df[df['Style'].astype(str).str.lower().isin([s.lower() for s in preferred_styles])]
            logger.info(f"After style filter: {len(df)} rows")
            
        if preferred_colors:
            if isinstance(preferred_colors, str):
                preferred_colors = [c.strip() for c in preferred_colors.split(',')]
            logger.info(f"Filtering by colors: {preferred_colors}")
            df = df[df['Color'].astype(str).str.lower().isin([c.lower() for c in preferred_colors])]
            logger.info(f"After color filter: {len(df)} rows")

        # Limit to 10 random products
        if len(df) > 10:
            df = df.sample(n=10, random_state=42)  # Fixed seed for reproducibility

        products = []
        for _, row in df.iterrows():
            try:
                name = f"{row['Style']} {row['Compartments']}"
                description = f"{row['Color']} {row['Material']} {row['Style']}"
                
                # Calculate price based on weight capacity
                try:
                    weight = float(row['Weight Capacity (kg)'])
                    price = int(weight * 10000)
                except (ValueError, TypeError):
                    price = 10000
                
                # Calculate quantity based on compartments
                try:
                    quantity = int(row['Compartments'])
                except (ValueError, TypeError):
                    quantity = 1
                
                # Handle image path
                filename = str(row['Image']).strip()
                if filename and filename != 'nan':
                    # Clean up the image path
                    filename = filename.replace('images/', '').replace('\\', '/').lstrip('/')
                    image = f"images/dataset/{filename}"
                else:
                    image = "images/dataset/default-bag.jpg"
                
                products.append({
                    'id': int(row['id']) if pd.notna(row['id']) else len(products) + 1,
                    'name': name,
                    'description': description,
                    'price': price,
                    'quantity': quantity,
                    'image': image,
                    'style': row['Style'],
                    'color': row['Color'],
                    'gender': row['Gender']
                })
            except Exception as e:
                logger.warning(f"Error processing row {row.get('id', 'unknown')}: {str(e)}")
                continue
        
        logger.info(f"Returning {len(products)} products")
        return products
        
    except Exception as e:
        logger.error(f"Error in get_product_data: {str(e)}")
        return []

# For CLI testing
if __name__ == "__main__":
    import argparse
    import json
    parser = argparse.ArgumentParser()
    parser.add_argument('--get_products', action='store_true', help='Output product data for ML')
    parser.add_argument('--gender', type=str, default=None)
    parser.add_argument('--styles', type=str, default=None, help='Comma-separated styles')
    parser.add_argument('--colors', type=str, default=None, help='Comma-separated colors')
    parser.add_argument('--train', action='store_true', help='Train the recommendation model')
    args = parser.parse_args()

    if args.get_products:
        styles = args.styles
        colors = args.colors
        products = get_product_data(user_gender=args.gender, preferred_styles=styles, preferred_colors=colors)
        print(json.dumps(products))
        exit(0)
    elif args.train:
        result = train_recommendation_model()
        print(result)
        exit(0)
    else:
        # Default behavior: train model and test
        print(train_recommendation_model())
        # Test with a sample product ID
        recommendations = get_recommendations(1)
        print(f"Sample recommendations: {len(recommendations)} products")
        # Test product data extraction
        products = get_product_data(user_gender='female', preferred_styles=['Puffer'], preferred_colors=['Black'])
        print(f"Sample product data: {len(products)} products")
        print(json.dumps(products[:2], indent=2))  # Show first 2 products