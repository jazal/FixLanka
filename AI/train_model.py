import pandas as pd
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.naive_bayes import MultinomialNB
import joblib

# Load dataset from CSV
data = pd.read_csv("complaints_data.csv")  # CSV must have 'description' and 'category' columns

# Vectorize complaint descriptions
vectorizer = TfidfVectorizer()
X = vectorizer.fit_transform(data['description'])  # complaint text
y = data['category']  # department/category

# Train the model
model = MultinomialNB()
model.fit(X, y)

# Save trained model and vectorizer
joblib.dump(model, "complaint_model.pkl")
joblib.dump(vectorizer, "vectorizer.pkl")

print("✅ Model trained and saved!")
