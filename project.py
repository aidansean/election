from project_module import project_object, image_object, link_object, challenge_object

p = project_object('election', 'General election data')
p.domain = 'http://www.aidansean.com/'
p.folder_name = 'aidansean'
p.path = 'election'
p.preview_image    = image_object('%s/images/project.jpg'   %p.path, 150, 250)
p.preview_image_bw = image_object('%s/images/project_bw.jpg'%p.path, 150, 250)
p.github_repo_name = 'election'
p.mathjax = False
p.tags = 'Data analysis'
p.technologies = 'CSS,HTML,MySQL,PHP,YouTube'
p.links.append(link_object(p.domain, 'election', 'Live page'))
p.introduction = 'This project displays the results of the 2010 UK General Electrion, and it was to be the first of many that investigate different data sets.  One of my planned meta projects is to make the acquisition, storage, and analysis of public domain data simpler and easier.  This was an experimental project, and created in a hurry.'
p.overview = '''The source of data was identified (BBC News website) and custom functions written to extract the HTML sources via HTTP requests.  The HTML sources were there parsed to determine the relevant information and written to file.  These files were then used to create PHP files to create and populate MySQL tables.  The remaining pages then allow the user to browse the data, sorting by various fields, with links back to the original sources.

This project should be revisited in the future to clean up the code and make the styles match those of the rest of the wider website.  This should be done in time for the next election!'''

p.challenges.append(challenge_object('Data must be taken from a public domain resource.', 'This project\'s first challenge was at the heart of the concept, which is how to obtain large amounts of publically available data in as few HTTP requests as possible, and in an ethical manner (ie avoiding overloading the servers which provide the data).  The HTTP requests were automated and the data dumped to file for further processing.', 'Resolved'))

p.challenges.append(challenge_object('The data for each constituency varies in its content and structure.', 'The data contains various parties and candidates which vary from constituency to constituency.  As a result the data processing must be able to add an arbitrary list of candidates and parties per constituency.  This turned out to be trivial, using the database schema, but did add another layer of complexity to the project.', 'Resolved'))