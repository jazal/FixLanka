import sys
import joblib

# Load saved model and vectorizer
model = joblib.load("complaint_model.pkl")
vectorizer = joblib.load("vectorizer.pkl")

# Get complaint from command-line arg
complaint = sys.argv[1]

# Predict category
X = vectorizer.transform([complaint])
prediction = model.predict(X)

print(prediction[0])
