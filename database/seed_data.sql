-- ──────────────────────────────────────────────────────────
--  seed_data.sql — Populate MemoryVerse with dummy data
-- ──────────────────────────────────────────────────────────

USE memoryverse;

-- 1. CLEAR EXISTING DATA
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE connections;
TRUNCATE TABLE memories;
SET FOREIGN_KEY_CHECKS = 1;

-- 2. INSERT DUMMY MEMORIES (40-60 items)
-- Grouped by clusters to fulfill Task 4 requirements

-- CAREER CLUSTER (Right Side)
INSERT INTO memories (id, title, description, category, emotion, date, worldX, worldY, size) VALUES
(1, 'Promotion to Senior', 'Finally achieved the senior developer role after three years of hard work.', 'Career', 'Achievement', '2024-03-12', 1050, 400, 48),
(2, 'First Big Presentation', 'Presented the architecture plan to the CEO and the board.', 'Career', 'Excited', '2023-11-05', 1100, 250, 42),
(3, 'Quitting the Old Job', 'A bittersweet farewell to my first workplace and great colleagues.', 'Career', 'Sad', '2022-08-15', 950, 550, 40),
(4, 'Landing the Internship', 'The moment I got the call from Google for my summer internship.', 'Career', 'Happy', '2021-02-20', 1200, 300, 46),
(5, 'Launch Day Success', 'The app went live and hit 10k users in the first hour.', 'Career', 'Excited', '2025-01-10', 1000, 150, 44),
(6, 'Dealing with Burnout', 'Took a month off to recover from a high-pressure project delivery.', 'Career', 'Peaceful', '2023-06-30', 920, 680, 38),
(7, 'Mentoring a Junior', 'Started helping Sarah with her coding progress. It feels great to give back.', 'Career', 'Love', '2024-09-05', 1080, 600, 40),
(8, 'Code Refactor Victory', 'Cleaned up 5000 lines of legacy code into a modular structure.', 'Career', 'Achievement', '2022-12-15', 1150, 480, 42),
(9, 'Meeting my Coding Hero', 'Got to chat with Dan Abramov at a React conference.', 'Career', 'Excited', '2023-04-22', 1250, 180, 45),
(10, 'Side Project Goes Viral', 'A small utility I wrote for VS Code got 500 stars on GitHub.', 'Career', 'Happy', '2024-10-18', 1100, 720, 39);

-- EDUCATION CLUSTER (Bottom-Left)
INSERT INTO memories (id, title, description, category, emotion, date, worldX, worldY, size) VALUES
(11, 'University Graduation', 'Walking across the stage with my Computer Science degree.', 'Education', 'Achievement', '2021-06-15', 250, 850, 48),
(12, 'Late Night Library Session', 'Drinking too much coffee while studying for the Algorithms final.', 'Education', 'Sad', '2020-12-10', 120, 920, 36),
(13, 'Passing Calculus III', 'The relief of seeing my grade after a semester of struggling.', 'Education', 'Peaceful', '2020-05-20', 350, 780, 40),
(14, 'Joining the Robotics Club', 'Met my best friends while building a competitive battle bot.', 'Education', 'Excited', '2021-09-12', 420, 880, 44),
(15, 'Scholarship Award', 'Received notice that my tuition would be covered for the final year.', 'Education', 'Happy', '2020-08-01', 180, 760, 45),
(16, 'First Hello World', 'The moment I realized I could tell a computer what to do.', 'Education', 'Happy', '2020-01-15', 300, 960, 42),
(17, 'Failing Data Structures', 'A tough lesson in time management and asking for help.', 'Education', 'Sad', '2020-04-30', 100, 800, 38),
(18, 'Mastering React', 'Everything finally clicked after weeks of confusion with hooks.', 'Education', 'Achievement', '2022-03-10', 480, 820, 43),
(19, 'Dorm Room Move-in Day', 'Unpacking boxes and meeting my roommate for the first time.', 'Education', 'Excited', '2020-08-25', 150, 680, 41),
(20, 'Capstone Project Pitch', 'Our team demoed the VR classroom prototype to the faculty.', 'Education', 'Excited', '2021-05-05', 380, 940, 44);

-- TRAVEL CLUSTER (Top Area)
INSERT INTO memories (id, title, description, category, emotion, date, worldX, worldY, size) VALUES
(21, 'Backpacking Through Japan', 'Two weeks of temples, sushi, and incredible neon nights.', 'Travel', 'Happy', '2023-10-14', 600, 120, 47),
(22, 'Sunset in Santorini', 'Watching the white buildings glow gold as the sun dipped into the Aegean.', 'Travel', 'Love', '2022-07-22', 850, 150, 46),
(23, 'Lost in Paris', 'Misplaced my map and found the most charming café in a back alley.', 'Travel', 'Peaceful', '2022-09-05', 400, 180, 40),
(24, 'Northern Lights in Iceland', 'Standing in the freezing cold watching green waves dance above.', 'Travel', 'Excited', '2024-02-12', 1100, 100, 48),
(25, 'Swiss Alps Hike', 'The air was so thin but the view of the Matterhorn was breathtaking.', 'Travel', 'Achievement', '2023-08-19', 250, 140, 45),
(26, 'Safari in Kenya', 'Waking up to the sound of elephants near our campsite.', 'Travel', 'Excited', '2024-06-30', 720, 90, 44),
(27, 'Rainy Day in London', 'Walking across Tower Bridge under a grey, moody sky.', 'Travel', 'Sad', '2021-11-20', 520, 240, 39),
(28, 'New York City Lights', 'The energy of Times Square at midnight was unlike anything else.', 'Travel', 'Excited', '2023-12-28', 980, 220, 43),
(29, 'Bali Yoga Retreat', 'Finding inner peace among the rice terraces and tropical birds.', 'Travel', 'Peaceful', '2024-05-15', 150, 250, 42),
(30, 'Road Trip Coast to Coast', 'Driving for 3000 miles across the American Heartland.', 'Travel', 'Happy', '2021-08-10', 1250, 150, 46);

-- PERSONAL CLUSTER (Center Area)
INSERT INTO memories (id, title, description, category, emotion, date, worldX, worldY, size) VALUES
(31, 'Adopting my Dog, Pixel', 'The moment this little golden retriever chose me at the shelter.', 'Personal', 'Love', '2022-04-18', 650, 450, 48),
(32, 'Learning to Bake Sourdough', 'My first loaf actually rose! It tasted like victory (and yeast).', 'Personal', 'Achievement', '2020-04-25', 780, 380, 40),
(33, 'Grandpa Passing Away', 'Saying goodbye to the man who taught me how to fix anything.', 'Personal', 'Sad', '2021-01-30', 520, 520, 38),
(34, 'Marriage Proposal', 'She said yes under a canopy of fairy lights in the garden.', 'Personal', 'Love', '2024-12-24', 680, 620, 49),
(35, 'Moving into New Apartment', 'The first night sleeping in a place that finally felt like mine.', 'Personal', 'Happy', '2023-09-01', 820, 580, 46),
(36, 'Learning the Guitar', 'Finally being able to play Wish You Were Here without mistakes.', 'Personal', 'Achievement', '2022-06-15', 470, 390, 41),
(37, 'First Marathon Finish', 'My legs were jelly but the medal felt so heavy and real.', 'Personal', 'Achievement', '2023-11-12', 880, 420, 47),
(38, 'Quiet Sunday Morning', 'Coffee, a book, and the sunlight hitting the floorboards just right.', 'Personal', 'Peaceful', '2025-02-09', 600, 320, 42),
(39, 'Bad Breakup', 'The end of a three-year relationship. Feeling hollow but surviving.', 'Personal', 'Sad', '2021-10-05', 420, 620, 37),
(40, 'Childhood Home Visit', 'Everything looked so much smaller than I remembered.', 'Personal', 'Peaceful', '2023-05-20', 550, 680, 44),
(41, 'Buying my First Car', 'A beat-up old Honda that represented total freedom.', 'Personal', 'Happy', '2021-03-25', 730, 500, 43),
(42, 'Volunteering at the Kitchen', 'Feeding others reminded me of how much I actually have.', 'Personal', 'Love', '2022-11-20', 580, 750, 41),
(43, 'Summer Lake Day', 'Jumping off the dock into the cold water with everyone laughing.', 'Personal', 'Happy', '2022-07-04', 900, 350, 45),
(44, 'First Solo Art Show', 'Seeing my paintings on a gallery wall was surreal.', 'Personal', 'Achievement', '2024-08-28', 500, 440, 44),
(45, 'Rainy Reading Session', 'Finished a 800-page novel while thunder rolled outside.', 'Personal', 'Peaceful', '2020-09-12', 400, 350, 39);

-- 3. INSERT CONNECTIONS (20-30 pairs)
-- Connecting related life events

INSERT INTO connections (from_id, to_id) VALUES
-- Education leads to Career
(11, 4), (16, 11), (20, 5), (18, 8),
-- Career progression
(4, 1), (1, 2), (3, 1),
-- Personal & Travel intersections
(34, 22), (31, 23), (41, 30),
-- Achievement connections
(11, 37), (1, 35), (15, 11),
-- Thematic Emotional Connections
(33, 40), (39, 6), (27, 33), (12, 17),
-- Travel Journey
(21, 24), (25, 21), (30, 28),
-- Skill growth
(36, 44), (32, 38), (18, 10);

-- Final check on data
-- SELECT * FROM memories;
-- SELECT * FROM connections;
