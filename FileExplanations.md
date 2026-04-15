# File Explanations for MemoryVerse Project

This project is called MemoryVerse, which seems to be an app for storing and visualizing memories in an interactive way, like a universe of life events. Below, I'll explain each file in the project in simple language.

## index.html
This is the main webpage file. It's the front part of the app that users see in their browser. It has HTML for the structure, CSS for making it look cool with a space theme (stars, nebulas, etc.), and probably some JavaScript to make it interactive. Users can view and interact with their memories here, like dragging planets around in a virtual universe.

## README.md
This is a guide file that explains how to set up and use the backend part of the app. It tells you how to create the database, set up the files on a server, and how to use the API endpoints. It's like instructions for developers to get the app running.

## schema.sql
This is a SQL file that creates the database for the app. It sets up two main tables: one for storing memories (like title, description, date, position in the universe) and another for connections between memories (like linking related events). Run this once to build the database structure.

## backend/api/connections.php
This is a PHP file that acts as an API endpoint for handling connections between memories. It can:
- Get all connections (show links between memories)
- Add new connections (create links between two memories)
It uses the database to store and retrieve this info, and sends responses in JSON format so the frontend can use it.

## backend/api/memories.php
This is a PHP file for the API that handles memories. It supports:
- Getting all memories (list them out)
- Adding new memories (create a new one)
- Updating existing memories (change details or position)
- Deleting memories (remove one)
It connects to the database and handles all the CRUD operations (Create, Read, Update, Delete) for memories.

## backend/config/db.php
This PHP file sets up the database connection. It has the database details like host, username, password, and database name. It includes a function called `get_db()` that creates and returns a connection to the database. Other API files use this to talk to the database.

## database/fix_schema.sql
This SQL file fixes or updates the database schema. It recreates the connections table, renames some columns in the memories table (like changing world_x to worldX), and updates descriptions. It's probably used to correct issues or align with changes in the app.

## database/schema.sql
This file is empty. It might be a placeholder or duplicate of the main schema.sql file.

## database/seed_data.sql
This SQL file adds dummy data to the database for testing. It inserts sample memories in different categories (Career, Education, Travel, Personal) with positions in the universe, and creates connections between them. This helps developers see how the app works with real data without adding their own.